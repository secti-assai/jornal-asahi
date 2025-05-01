@extends('layouts.app')

@section('title', 'Nossa Equipe - Jornal Asahi')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="text-center">Nossa Equipe</h1>
            <p class="text-center lead">Conheça as pessoas que fazem o Jornal Asahi acontecer</p>
        </div>
    </div>

    <!-- SEÇÃO DE AUTORIDADES -->
    <section class="mb-5">
        <h2 class="text-center mb-4">Autoridades</h2>
        
        <!-- Prefeito -->
        <div class="row mb-5 justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card border-0 shadow authority-card">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <div class="p-3 text-center">
                                <img src="{{ asset('storage/authorities/tuti.jpg') }}" 
                                     alt="Michel Ângelo Bomtempo" class="rounded-circle authority-avatar">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h3 class="card-title">Michel Ângelo Bomtempo</h3>
                                <h5 class="card-subtitle mb-2 text-primary">Prefeito de Assaí</h5>
                                <p class="card-text">Apoiador e incentivador do projeto Jornal Asahi, trabalhando para valorizar a educação, comunicação e o protagonismo dos jovens do município.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Secretários -->
        <h3 class="text-center mb-4">Secretários</h3>
        <div class="row mb-5">
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow h-100 authority-card">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <div class="p-3 text-center">
                                <img src="{{ asset('storage/authorities/igor.jpg') }}" 
                                     alt="Igor" class="rounded-circle authority-avatar">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h4 class="card-title">Igor Oliveira</h4>
                                <h6 class="card-subtitle mb-2 text-info">Secretário de Ciência, Tecnologia e Inovação</h6>
                                <p class="card-text">Atua na promoção de atividades tecnológicos e projetos que enriquecem a formação dos jovens de Assaí.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 mb-4">
                <div class="card border-0 shadow h-100 authority-card">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <div class="p-3 text-center">
                                <img src="{{ asset('storage/authorities/josi.jpg') }}" 
                                     alt="Josi" class="rounded-circle authority-avatar">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h4 class="card-title">Josiane Cheffer</h4>
                                <h6 class="card-subtitle mb-2 text-info">Secretária de Educação</h6>
                                <p class="card-text">Responsável por coordenar as políticas educacionais do município e apoiar iniciativas como o Jornal Asahi.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Separador -->
    <div class="row mb-5">
        <div class="col-12">
            <hr>
            <h2 class="text-center">Repórteres do Jornal Asahi</h2>
            <p class="text-center mb-4">Nossos jovens talentos que produzem o conteúdo do jornal</p>
        </div>
    </div>

    <!-- LISTA DE REPÓRTERES -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
        @foreach($reporters as $reporter)
        <div class="col">
            <div class="card team-card h-100 border-0 shadow-sm">
                <div class="card-body text-center">
                    @if($reporter->profile_image)
                        <img src="{{ asset('storage/' . $reporter->profile_image) }}" 
                             alt="{{ $reporter->name }}" class="rounded-circle mb-3"
                             style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3"
                             style="width: 100px; height: 100px; font-size: 36px;">
                            {{ substr($reporter->name, 0, 2) }}
                        </div>
                    @endif
                    
                    <h5 class="card-title mb-1">{{ $reporter->name }}</h5>
                    <p class="text-muted small mb-2">{{ '@'.$reporter->username }}</p>
                    
                    <div class="d-flex justify-content-center mb-3">
                        <span class="badge bg-primary me-1">{{ $reporter->role->name }}</span>
                        <span class="badge bg-secondary me-1">{{ $reporter->education_level }}</span>
                        
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
                    
                    <p class="card-text small">{{ \Illuminate\Support\Str::limit($reporter->description, 100) }}</p>
                    
                    <!-- Botão para ver perfil completo -->
                    <a href="{{ route('profile.public', $reporter->username) }}" class="btn btn-sm btn-outline-primary">
                        Ver perfil completo
                    </a>
                </div>
            </div>
        </div>
        @endforeach
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

    .authority-avatar {
        width: 100px;
        height: 100px;
        object-fit: cover;
    }
</style>
@endpush
@endsection