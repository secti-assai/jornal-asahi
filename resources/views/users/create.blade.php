@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <h1>Novo Usuário</h1>
        
        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Senha</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
            </div>
            
            <div class="mb-3">
                <label for="role_id" class="form-label">Função</label>
                <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                    <option value="">Selecione uma função</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Adicione estes campos ao formulário de criação -->

            <div class="mb-3">
                <label for="username" class="form-label">Nome de usuário</label>
                <div class="input-group">
                    <span class="input-group-text">@</span>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" 
                           id="username" name="username" value="{{ old('username') }}" required>
                </div>
                @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Nome de usuário único sem espaços (ex: ana_laura)</small>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Uma breve descrição sobre o usuário</small>
            </div>

            <div class="mb-3">
                <label for="education_level" class="form-label">Escolaridade</label>
                <select class="form-select @error('education_level') is-invalid @enderror" 
                        id="education_level" name="education_level" required>
                    <option value="">Selecione o nível de escolaridade</option>
                    <option value="Ensino Fundamental" {{ old('education_level') == 'Ensino Fundamental' ? 'selected' : '' }}>
                        Ensino Fundamental
                    </option>
                    <option value="Ensino Médio" {{ old('education_level') == 'Ensino Médio' ? 'selected' : '' }}>
                        Ensino Médio
                    </option>
                    <option value="Ensino Superior" {{ old('education_level') == 'Ensino Superior' ? 'selected' : '' }}>
                        Ensino Superior
                    </option>
                </select>
                @error('education_level')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Adicione este novo campo após o campo de educação -->

            <div class="mb-3">
                <label for="relation" class="form-label">Relação</label>
                <select class="form-select @error('relation') is-invalid @enderror" id="relation" name="relation" required>
                    <option value="">Selecione a relação</option>
                    <option value="Rua" {{ old('relation') == 'Rua' ? 'selected' : '' }}>Rua</option>
                    <option value="Âncora" {{ old('relation') == 'Âncora' ? 'selected' : '' }}>Âncora</option>
                    <option value="Marketing" {{ old('relation') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                </select>
                @error('relation')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="form-text text-muted">Selecione a função principal do usuário no jornal</small>
            </div>
            
            <div class="mb-3">
                <label for="profile_image" class="form-label">Foto de Perfil</label>
                <input type="file" class="form-control @error('profile_image') is-invalid @enderror" id="profile_image" name="profile_image" accept="image/*">
                <div class="form-text">Escolha uma imagem de perfil (opcional). Recomendado: formato quadrado, máx. 2MB</div>
                @error('profile_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                
                <div id="imagePreview" class="mt-2" style="display: none;">
                    <img src="#" alt="Preview" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                </div>
            </div>
            
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('users.index') }}" class="btn btn-secondary me-md-2">Cancelar</a>
                <button type="submit" class="btn btn-primary">Criar Usuário</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const profileImageInput = document.getElementById('profile_image');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = imagePreview.querySelector('img');
        
        profileImageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];
                
                // Verificar tamanho do arquivo (máximo 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('A imagem não pode ter mais que 2MB');
                    this.value = '';
                    imagePreview.style.display = 'none';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    imagePreview.style.display = 'block';
                };
                
                reader.readAsDataURL(file);
            } else {
                imagePreview.style.display = 'none';
            }
        });
    });
</script>
@endpush