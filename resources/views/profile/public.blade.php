@extends('layouts.app')

@section('title', $user->name . ' - Perfil')

@section('content')
<div class="container py-4">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Início</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('team.index') }}">Equipe</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $user->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Coluna do perfil -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($user->profile_image)
                            <img src="{{ asset('storage/'.$user->profile_image) }}" 
                                 alt="{{ $user->name }}" 
                                 class="rounded-circle img-thumbnail" 
                                 style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" 
                                 alt="{{ $user->name }}" 
                                 class="rounded-circle img-thumbnail" 
                                 style="width: 150px; height: 150px;">
                        @endif
                    </div>
                    
                    <h3 class="h4 mb-1">{{ $user->name }}</h3>
                    <p class="text-muted mb-2">{{ '@'.$user->username }}</p>
                    
                    <div class="d-flex justify-content-center align-items-center mb-3">
                        <div class="badge bg-primary me-2">{{ $user->role->name }}</div>
                        <div class="badge bg-info me-2">{{ $user->education_level }}</div>
                        
                        @if($user->relation)
                            <div class="badge bg-secondary">{{ $user->relation }}</div>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-center align-items-center mb-3">
                        <span class="me-3">
                            <i class="bi bi-hand-thumbs-up-fill text-primary"></i> {{ $likesCount }}
                        </span>
                        <span>
                            <i class="bi bi-chat-dots-fill text-secondary"></i> 
                            {{ $interactions->where('comment', '!=', null)->count() }}
                        </span>
                    </div>
                    
                    @if($user->description)
                    <div class="card bg-light mb-3">
                        <div class="card-body py-3">
                            <p class="card-text">{{ $user->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Coluna de interações -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h2 class="h5 mb-0">Deixe sua opinião</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.interaction', $user->id) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="visitor_name" class="form-label">Seu nome</label>
                            <input type="text" class="form-control @error('visitor_name') is-invalid @enderror" 
                                  id="visitor_name" name="visitor_name" value="{{ old('visitor_name') }}" required>
                            @error('visitor_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="comment" class="form-label">Comentário</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" 
                                     id="comment" name="comment" rows="3" required>{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="like" name="like" value="1" {{ old('like') ? 'checked' : '' }}>
                            <label class="form-check-label" for="like">
                                <i class="bi bi-hand-thumbs-up"></i> Curtir o perfil deste repórter
                            </label>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h2 class="h5 mb-0">Comentários</h2>
                </div>
                <div class="card-body">
                    <!-- Exibir comentários existentes -->
                    @if($interactions->where('comment', '!=', null)->count() > 0)
                        <div class="mb-3">
                            @foreach($interactions->where('comment', '!=', null) as $interaction)
                                <div class="border-bottom pb-3 pt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="mb-0">
                                            @if($interaction->visitor)
                                                {{ $interaction->visitor->name }}
                                            @else
                                                {{ $interaction->visitor_name ?? 'Anônimo' }}
                                            @endif
                                        </h5>
                                        <small class="text-muted">{{ $interaction->created_at->format('d/m/Y H:i') }}</small>
                                    </div>
                                    <p class="mb-1">{{ $interaction->comment }}</p>
                                    @if($interaction->like)
                                        <div class="mt-1">
                                            <i class="bi bi-hand-thumbs-up-fill text-primary"></i>
                                            <small class="text-muted">Curtiu este perfil</small>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-light text-center py-4">
                            <p class="mb-0">Ainda não há comentários para este perfil. Seja o primeiro a comentar!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection