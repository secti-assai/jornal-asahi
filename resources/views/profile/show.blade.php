@extends('layouts.app')

@section('title', 'Meu Perfil')

@section('content')
<div class="container py-4">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="section-title mb-0">Meu Perfil</h1>
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                    <i class="bi bi-pencil me-1"></i> Editar Perfil
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Coluna do perfil -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($user->profile_image)
                            <img src="{{ asset('storage/'.$user->profile_image) }}" alt="{{ $user->name }}" 
                                 class="rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random" 
                                 alt="{{ $user->name }}" class="rounded-circle img-thumbnail" 
                                 style="width: 150px; height: 150px;">
                        @endif
                    </div>
                    <h3 class="h4 mb-1">{{ $user->name }}</h3>
                    <p class="text-muted mb-2">{{ '@'.$user->username }}</p>
                    <p class="mb-2">
                        <span class="badge bg-primary">{{ $user->role->name }}</span>
                        <span class="badge bg-secondary">{{ $user->education_level }}</span>
                    </p>
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
                        <div class="card-body py-2">
                            <p class="card-text">{{ $user->description }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Coluna de interações -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h2 class="h5 mb-0">Comentários e interações</h2>
                </div>
                <div class="card-body">
                    <!-- Exibir interações existentes -->
                    @if($interactions->where('comment', '!=', null)->count() > 0)
                        <div class="mb-4">
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
                                    <p class="mb-0">{{ $interaction->comment }}</p>
                                    @if($interaction->like)
                                        <div class="mt-2">
                                            <i class="bi bi-hand-thumbs-up-fill text-primary"></i>
                                            <small class="text-muted">Curtiu seu perfil</small>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert alert-light text-center py-4" role="alert">
                            <p class="mb-0">Ainda não há comentários em seu perfil.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection