@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <h1>Editar Usuário</h1>
        
        <form action="{{ route('users.update', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="role_id" class="form-label">Função</label>
                <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id" required>
                    <option value="">Selecione...</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                            {{ $role->name }} (Nível {{ $role->level }})
                        </option>
                    @endforeach
                </select>
                @error('role_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Adicione estes campos ao formulário de edição -->

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
                <label for="description" class="form-label">Descrição</label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="3">{{ old('description', $user->description) }}</textarea>
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
                <label for="password" class="form-label">Nova Senha (deixe em branco para manter a atual)</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirmar Nova Senha</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>

            <div class="mb-3">
                <label for="profile_image" class="form-label">Foto de Perfil</label>
                <input type="file" class="form-control" id="profile_image" name="profile_image" accept="image/*">
                <div class="form-text">Escolha uma imagem de perfil (opcional). Recomendado: formato quadrado, máx. 2MB</div>
                
                @if($user->profile_image)
                    <div class="mt-2">
                        <p>Imagem atual:</p>
                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" 
                             class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                    </div>
                @endif
                
                <div id="imagePreview" class="mt-2" style="display: none;">
                    <p>Nova imagem:</p>
                    <img src="#" alt="Preview" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                </div>
            </div>
            
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Atualizar</button>
                <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
@endsection