@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <h1>Editar Notícia</h1>
        
        <form action="{{ route('news.update', $news) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="title" class="form-label">Título</label>
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $news->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="content" class="form-label">Conteúdo</label>
                <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="10" required>{{ old('content', $news->content) }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="image" class="form-label">Imagem</label>
                @if($news->image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $news->image) }}" alt="{{ $news->title }}" style="max-width: 200px;">
                    </div>
                @endif
                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" 
                       name="image" accept="image/jpeg,image/png,image/gif,image/jpg" 
                       data-max-size="2097152">
                <small class="text-muted">Tamanho máximo: 2MB. Formatos permitidos: JPG, PNG, GIF.</small>
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <div id="imagePreview" class="mt-2" style="display: none;">
                    <img src="#" alt="Preview" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                </div>
            </div>
            
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Definir adapter personalizado para upload
    class MyUploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }

        upload() {
            return this.loader.file
                .then(file => {
                    return new Promise((resolve, reject) => {
                        const data = new FormData();
                        data.append('upload', file);
                        
                        // Adicionar token CSRF
                        data.append('_token', '{{ csrf_token() }}');
                        
                        fetch('{{ route("ckeditor.upload") }}', {
                            method: 'POST',
                            body: data,
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(responseData => {
                            console.log('Resposta do servidor:', responseData);
                            
                            if (!responseData || responseData.error) {
                                console.error('Erro retornado pelo servidor:', responseData.error || 'Resposta inválida');
                                reject(responseData && responseData.error ? responseData.error.message : 'Upload falhou');
                                return;
                            }
                            
                            if (!responseData.url) {
                                console.error('URL não encontrada na resposta');
                                reject('Formato de resposta inválido');
                                return;
                            }
                            
                            resolve({
                                default: responseData.url
                            });
                        })
                        .catch(error => {
                            console.error('Erro durante upload:', error);
                            reject('Falha na comunicação com o servidor');
                        });
                    });
                });
        }

        abort() {
            // Cancelar upload se necessário
        }
    }

    function MyCustomUploadAdapterPlugin(editor) {
        editor.plugins.get('FileRepository').createUploadAdapter = (loader) => {
            return new MyUploadAdapter(loader);
        };
    }

    ClassicEditor
        .create(document.querySelector('#content'), {
            extraPlugins: [MyCustomUploadAdapterPlugin],
            toolbar: {
                items: [
                    'heading', '|',
                    'fontfamily', 'fontsize', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'alignment', '|',
                    'bulletedList', 'numberedList', '|',
                    'indent', 'outdent', '|',
                    'link', 'blockQuote', 'insertTable', 'imageUpload', '|',
                    'undo', 'redo'
                ]
            },
            image: {
                toolbar: [
                    'imageTextAlternative', '|',
                    'imageStyle:alignLeft', 'imageStyle:alignCenter', 'imageStyle:alignRight'
                ],
                styles: [
                    'alignLeft', 'alignCenter', 'alignRight'
                ]
            }
        })
        .then(editor => {
            console.log('Editor inicializado com sucesso');
            window.editor = editor;
        })
        .catch(error => {
            console.error('Erro ao inicializar o editor:', error);
        });
});
</script>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const imageInput = document.getElementById('image');
        
        // Adicionar validação de tamanho ao input de imagem
        imageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                const maxSize = 2 * 1024 * 1024; // 2MB em bytes
                
                if (file.size > maxSize) {
                    // Mostrar alerta e limpar o campo
                    Swal.fire({
                        icon: 'error',
                        title: 'Arquivo muito grande',
                        text: 'A imagem não pode ter mais que 2MB. O arquivo selecionado tem ' + 
                              (file.size / (1024 * 1024)).toFixed(2) + 'MB',
                        confirmButtonText: 'Entendi'
                    });
                    
                    // Limpar o campo de input
                    this.value = '';
                    return;
                }
                
                // Mostrar preview da imagem se estiver ok
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewImg = document.querySelector('#imagePreview img');
                    previewImg.src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                };
                
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endpush