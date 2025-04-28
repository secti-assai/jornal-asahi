@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Nova Notícia</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('news.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Título</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
        </div>
        
        <div class="mb-3">
            <label for="content" class="form-label">Conteúdo</label>
            <textarea class="form-control @error('content') is-invalid @enderror" 
                      id="content" name="content" rows="10">{{ old('content') }}</textarea>
            <div class="form-text">
                O conteúdo deve ter no máximo 65.000 caracteres. Imagens e formatações podem aumentar este limite.
            </div>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="image" class="form-label">Imagem</label>
            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif">
            <div class="form-text">Apenas imagens (JPG, PNG, GIF) são permitidas. Tamanho máximo: 2MB.</div>
            @error('image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
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
                            if (!responseData || responseData.uploaded !== 1) {
                                reject(responseData && responseData.error ? responseData.error.message : 'Upload falhou');
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