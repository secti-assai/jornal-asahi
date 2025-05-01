@extends('layouts.app')

@section('title', 'Entrevistas - Jornal Asahi')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-3">Entrevistas</h1>
            <p class="lead">Conversas e depoimentos com personalidades de Assaí e região</p>
        </div>
    </div>
    
    @if(isset($featuredInterview) && $featuredInterview)
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0 overflow-hidden">
                <div class="ratio ratio-16x9">
                    <iframe 
                        src="https://www.youtube.com/embed/{{ $featuredInterview->youtube_video_id }}?rel=0" 
                        title="{{ $featuredInterview->title }}" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h3 class="card-title h4">{{ $featuredInterview->title }}</h3>
                        <span class="badge bg-primary">Destaque</span>
                    </div>
                    @if($featuredInterview->interviewee)
                    <p class="text-muted mb-2">
                        <i class="bi bi-person"></i> 
                        Entrevista com: {{ $featuredInterview->interviewee }}
                    </p>
                    @endif
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-calendar-event me-1"></i>
                        <small class="text-muted">
                            {{ $featuredInterview->interview_date ? $featuredInterview->interview_date->format('d/m/Y') : 'Data não definida' }}
                        </small>
                    </div>
                    <p class="card-text">{{ $featuredInterview->description }}</p>
                    <a href="https://www.youtube.com/watch?v={{ $featuredInterview->youtube_video_id }}" 
                       class="btn btn-danger" target="_blank">
                        <i class="bi bi-youtube me-1"></i> Assistir no YouTube
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($interviews->isEmpty())
    <div class="row">
        <div class="col-12">
            <div class="alert alert-info">
                Nenhuma entrevista disponível no momento.
            </div>
        </div>
    </div>
    @else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($interviews as $interview)
            @if(!isset($featuredInterview) || $interview->id !== $featuredInterview->id)
            <div class="col">
                <div class="card shadow-sm border-0 h-100">
                    <div class="ratio ratio-16x9">
                        <img src="https://img.youtube.com/vi/{{ $interview->youtube_video_id }}/mqdefault.jpg" 
                             alt="{{ $interview->title }}"
                             class="card-img-top interview-thumbnail">
                        <div class="overlay-play">
                            <a href="https://www.youtube.com/watch?v={{ $interview->youtube_video_id }}" 
                               target="_blank" class="play-button">
                                <i class="bi bi-play-circle-fill"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <h3 class="card-title h6">{{ $interview->title }}</h3>
                        @if($interview->interviewee)
                        <p class="text-muted small mb-1">
                            <i class="bi bi-person"></i> {{ $interview->interviewee }}
                        </p>
                        @endif
                        <p class="card-text small">{{ Str::limit($interview->description, 100) }}</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-calendar-event"></i>
                            {{ $interview->interview_date ? $interview->interview_date->format('d/m/Y') : 'Data não definida' }}
                        </small>
                        <a href="https://www.youtube.com/watch?v={{ $interview->youtube_video_id }}" 
                           class="btn btn-sm btn-outline-danger" target="_blank">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endif
        @endforeach
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $interviews->links() }}
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .interview-thumbnail {
        object-fit: cover;
        width: 100%;
        height: 100%;
    }
    
    .overlay-play {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .card:hover .overlay-play {
        opacity: 1;
    }
    
    .play-button {
        font-size: 3rem;
        color: white;
        text-shadow: 0 0 10px rgba(0,0,0,0.5);
    }
    
    .play-button:hover {
        color: #ff0000;
        transform: scale(1.1);
        transition: all 0.3s ease;
    }
</style>
@endpush