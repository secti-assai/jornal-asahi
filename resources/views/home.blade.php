@extends('layouts.app')

@section('content')
<div class="container py-4">
    <header class="mb-5">
        <div class="row">
            <div class="col-12">
                <h1 class="display-4 text-center mb-4">Portal de Notícias</h1>
                <hr class="my-4">
            </div>
        </div>
    </header>

    @if($featuredNews && $featuredNews->count() > 0)
    <section class="featured-news mb-5">
        <h2 class="h4 pb-2 mb-4 border-bottom">Destaques</h2>
        <div id="newsCarousel" class="carousel slide shadow" data-bs-ride="carousel">
            <div class="carousel-indicators">
                @foreach($featuredNews as $index => $item)
                    <button type="button" data-bs-target="#newsCarousel" data-bs-slide-to="{{ $index }}" 
                        class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}" 
                        aria-label="Slide {{ $index + 1 }}"></button>
                @endforeach
            </div>
            <div class="carousel-inner rounded">
                @foreach($featuredNews as $index => $item)
                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" class="d-block w-100" alt="{{ $item->title }}" 
                                style="height: 400px; object-fit: cover;">
                        @else
                            <div class="bg-secondary d-block w-100" style="height: 400px; display: flex; align-items: center; justify-content: center;">
                                <span class="text-white">Sem imagem disponível</span>
                            </div>
                        @endif
                        <div class="carousel-caption d-none d-md-block" style="background: rgba(0,0,0,0.6); border-radius: 8px; padding: 20px;">
                            <h3>{{ $item->title }}</h3>
                            <p>{{ Str::limit(strip_tags($item->content), 150) }}</p>
                            <a href="{{ route('news.show', $item) }}" class="btn btn-primary">Ler mais</a>
                        </div>
                    </div>
                @endforeach
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#newsCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#newsCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Próximo</span>
            </button>
        </div>
    </section>
    @endif

        <!-- Live Stream Section - Add this new section -->
        @if(isset($activeLiveStream))
    <section class="live-stream mb-5">
        <h2 class="h4 pb-2 mb-4 border-bottom">Transmissão ao Vivo</h2>
        <div class="card shadow">
            <div class="ratio ratio-16x9">
                <iframe 
                    src="https://www.youtube.com/embed/{{ $activeLiveStream->youtube_video_id }}?rel=0" 
                    title="{{ $activeLiveStream->title }}" 
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                    allowfullscreen>
                </iframe>
            </div>
            <div class="card-body">
                <h3 class="card-title">{{ $activeLiveStream->title }}</h3>
                @if($activeLiveStream->description)
                    <p class="card-text">{{ $activeLiveStream->description }}</p>
                @endif
                <a href="https://www.youtube.com/watch?v={{ $activeLiveStream->youtube_video_id }}" 
                   class="btn btn-danger" target="_blank">
                    <i class="bi bi-youtube"></i> Assistir no YouTube
                </a>
            </div>
        </div>
    </section>
    @endif

    <section class="latest-news">
        <h2 class="h4 pb-2 mb-4 border-bottom">Últimas Notícias</h2>
        
        @if($latestNews && $latestNews->count() > 0)
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach($latestNews as $item)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->title }}" 
                                    style="height: 180px; object-fit: cover;">
                            @else
                                <div class="bg-light text-center py-5">
                                    <i class="bi bi-image text-secondary" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->title }}</h5>
                                <p class="card-text small text-muted">
                                    <i class="bi bi-person-circle"></i> {{ $item->author->name }}
                                    <br>
                                    <i class="bi bi-calendar3"></i> {{ $item->published_at ? $item->published_at->format('d/m/Y H:i') : 'Não publicado' }}
                                </p>
                                <p class="card-text">{{ Str::limit(strip_tags($item->content), 100) }}</p>
                            </div>
                            <div class="card-footer bg-transparent">
                                <a href="{{ route('news.show', $item) }}" class="btn btn-sm btn-outline-primary w-100">Ler mais</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $latestNews->links() }}
            </div>
        @else
            <div class="alert alert-info">
                Não há notícias publicadas no momento.
            </div>
        @endif
    </section>
</div>
@endsection