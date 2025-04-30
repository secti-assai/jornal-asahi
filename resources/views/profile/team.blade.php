@extends('layouts.app')

@section('title', 'Nossa Equipe de Repórteres')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="h2 mb-2">Nossa Equipe de Repórteres</h1>
            <p class="text-muted">Conheça os jovens talentos que fazem parte do Jornal Asahi</p>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
        @forelse($reporters as $reporter)
            <div class="col">
                <div class="card team-card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        @if($reporter->profile_image)
                            <img src="{{ asset('storage/'.$reporter->profile_image) }}" 
                                 alt="{{ $reporter->name }}" 
                                 class="rounded-circle mb-3" 
                                 style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($reporter->name) }}&background=random" 
                                 alt="{{ $reporter->name }}" 
                                 class="rounded-circle mb-3" 
                                 style="width: 120px; height: 120px;">
                        @endif
                        
                        <h5 class="card-title mb-1">{{ $reporter->name }}</h5>
                        <p class="text-muted small mb-2">{{ '@'.$reporter->username }}</p>
                        
                        <div class="d-flex justify-content-center mb-3">
                            <span class="badge bg-primary me-1">{{ $reporter->role->name }}</span>
                            <span class="badge bg-info me-1">{{ $reporter->education_level }}</span>
                            
                            @if($reporter->relation)
                                <span class="badge 
                                    @if($reporter->relation == 'Rua') bg-success 
                                    @elseif($reporter->relation == 'Âncora') bg-info
                                    @elseif($reporter->relation == 'Marketing') bg-warning text-dark
                                    @endif">
                                    {{ $reporter->relation }}
                                </span>
                            @endif
                        </div>
                        
                        <p class="card-text small mb-3">
                            {{ \Illuminate\Support\Str::limit($reporter->description, 100) }}
                        </p>
                        
                        <a href="{{ route('profile.public', $reporter->username) }}" class="btn btn-sm btn-outline-primary">
                            Ver perfil completo
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center" role="alert">
                    <i class="bi bi-info-circle me-2"></i> Não há repórteres cadastrados no momento.
                </div>
            </div>
        @endforelse
    </div>
</div>

@push('styles')
<style>
    .team-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .team-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
    }
</style>
@endpush
@endsection