@extends('layouts.app')

@section('title', 'Editar Perfil')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="section-title">Editar Perfil</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="text-center mb-3">
                                    <div class="mb-3">
                                        @if($user->profile_image)
                                            <img src="{{ asset('storage/'.$user->profile_image) }}" alt="{{ $user->name }}" 
                                                class="rounded-circle img-thumbnail profile-preview" 
                                                style="width: 150px; height: 150px; object-fit: cover;">
                                        @else
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" 
                                                alt="{{ $user->name }}" class="rounded-circle img-thumbnail profile-preview" 
                                                style="width: 150px; height: 150px;">
                                        @endif
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="profile_image" class="form-label">Imagem de perfil</label>
                                        <input type="file" class="form-control" id="profile_image" name="profile_image">
                                        <small class="form-text text-muted">Recomendado: imagem quadrada, mín. 300x300px</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nome completo</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                        id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label for="username" class="form-label">Nome de usuário</label>
                                    <div class="input-group">
                                        <span class="input-group-text">@</span>
                                        <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                            id="username" name="username" value="{{ old('username', $user->username) }}" required>
                                    </div>
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Nome de usuário único sem espaços (ex: ana_laura)</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="education_level" class="form-label">Escolaridade</label>
                                    <select class="form-select @error('education_level') is-invalid @enderror" 
                                            id="education_level" name="education_level" required>
                                        <option value="">Selecione o nível de escolaridade</option>
                                        <option value="Ensino Fundamental" {{ old('education_level', $user->education_level) == 'Ensino Fundamental' ? 'selected' : '' }}>
                                            Ensino Fundamental
                                        </option>
                                        <option value="Ensino Médio" {{ old('education_level', $user->education_level) == 'Ensino Médio' ? 'selected' : '' }}>
                                            Ensino Médio
                                        </option>
                                        <option value="Ensino Superior" {{ old('education_level', $user->education_level) == 'Ensino Superior' ? 'selected' : '' }}>
                                            Ensino Superior
                                        </option>
                                    </select>
                                    @error('education_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="relation" class="form-label">Relação</label>
                                    <select class="form-select @error('relation') is-invalid @enderror" 
                                            id="relation" name="relation" required>
                                        <option value="">Selecione a relação</option>
                                        <option value="Rua" {{ old('relation', $user->relation) == 'Rua' ? 'selected' : '' }}>Rua</option>
                                        <option value="Âncora" {{ old('relation', $user->relation) == 'Âncora' ? 'selected' : '' }}>Âncora</option>
                                        <option value="Marketing" {{ old('relation', $user->relation) == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                    </select>
                                    @error('relation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Sua função principal no Jornal Asahi</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                id="description" name="description" rows="4">{{ old('description', $user->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Uma breve descrição sobre você (até 1000 caracteres)</small>
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary me-md-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Salvar alterações</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Preview da imagem de perfil
    document.addEventListener('DOMContentLoaded', function() {
        const inputFile = document.getElementById('profile_image');
        const previewImage = document.querySelector('.profile-preview');
        
        if (inputFile && previewImage) {
            inputFile.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                    }
                    
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
    });
</script>
@endpush
@endsection