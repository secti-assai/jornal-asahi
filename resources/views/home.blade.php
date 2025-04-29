@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Seção de destaques com slider -->
    @if($featuredNews && $featuredNews->count() > 0)
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="section-title">Notícias Recentes</h2>
            <p class="text-muted mb-4">Mantenha-se informado com as últimas notícias de Assaí</p>
            
            <div id="featuredNewsCarousel" class="carousel slide" data-bs-ride="carousel">
                <!-- Indicadores para dispositivos móveis (visíveis apenas em mobile) -->
                <div class="carousel-indicators mobile-indicators d-md-none" style="top: 10px; bottom: auto; margin-top: 0;">
                    @foreach($featuredNews as $index => $item)
                        <button type="button" data-bs-target="#featuredNewsCarousel" data-bs-slide-to="{{ $index }}" 
                            class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}" 
                            aria-label="Slide {{ $index + 1 }}"
                            style="background-color: rgba(255, 255, 255, 0.8); width: 30px; height: 3px; border-radius: 0;"></button>
                    @endforeach
                </div>

                <!-- Indicadores para desktop (visíveis apenas em tablet e desktop) -->
                <div class="carousel-indicators desktop-indicators d-none d-md-flex">
                    @foreach($featuredNews as $index => $item)
                        <button type="button" data-bs-target="#featuredNewsCarousel" data-bs-slide-to="{{ $index }}" 
                            class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}" 
                            aria-label="Slide {{ $index + 1 }}"></button>
                    @endforeach
                </div>

                <div class="carousel-inner rounded overflow-hidden">
                    @foreach($featuredNews as $index => $item)
                        <div class="carousel-item {{ $index == 0 ? 'active' : '' }}" data-bs-interval="6000">
                            <div class="position-relative">
                                @if($item->image)
                                    <img src="{{ asset('storage/' . $item->image) }}" class="d-block w-100" alt="{{ $item->title }}" 
                                        style="height: 500px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary d-block w-100" style="height: 500px; display: flex; align-items: center; justify-content: center;">
                                        <span class="text-white">Sem imagem disponível</span>
                                    </div>
                                @endif
                                <div class="position-absolute bottom-0 start-0 w-100 p-4" style="background: linear-gradient(transparent, rgba(0,0,0,0.8)); pointer-events: none;">
                                    <div class="container">
                                        <h2 class="text-white mb-2">{{ $item->title }}</h2>
                                        <p class="text-white-50 mb-3">{{ Str::limit(html_entity_decode(strip_tags($item->content)), 150) }}</p>
                                        <a href="{{ route('news.show', $item) }}" class="btn btn-sm btn-primary" style="pointer-events: auto; position: relative; z-index: 100;">Ler matéria completa</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#featuredNewsCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Anterior</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#featuredNewsCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Próximo</span>
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Transmissão ao Vivo -->
    @if(isset($activeLiveStream))
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="section-title m-0">
                    <i class="bi bi-broadcast live-indicator me-2"></i>
                    Transmissão ao Vivo
                </h2>
                <a href="#" class="text-decoration-none text-primary">Ver mais transmissões <i class="bi bi-arrow-right"></i></a>
            </div>
            
            <div class="card shadow border-0 overflow-hidden">
                <div class="ratio ratio-16x9">
                    <iframe 
                        src="https://www.youtube.com/embed/{{ $activeLiveStream->youtube_video_id }}?autoplay=0&rel=0" 
                        title="{{ $activeLiveStream->title }}" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h3 class="card-title h5">{{ $activeLiveStream->title }}</h3>
                        <span class="badge bg-danger"><i class="bi bi-circle-fill me-1"></i> AO VIVO</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-calendar-event me-2"></i>
                        <small class="text-muted">{{ date('d/m/Y, H:i', strtotime($activeLiveStream->start_time)) }}</small>
                    </div>
                    @if($activeLiveStream->description)
                        <p class="card-text">{{ $activeLiveStream->description }}</p>
                    @endif
                    <a href="https://www.youtube.com/watch?v={{ $activeLiveStream->youtube_video_id }}" 
                       class="btn btn-danger" target="_blank">
                        <i class="bi bi-youtube me-2"></i> Assistir no YouTube
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Últimas Notícias em Grid -->
    @if($latestNews && $latestNews->count() > 0)
        <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
            @foreach($latestNews as $item)
                <div class="col">
                    <div class="card h-100 border-0 shadow-sm">
                        @if($item->image)
                            <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->title }}" 
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-light text-center p-4">
                                <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title mb-3">{{ $item->title }}</h5>
                            <div class="d-flex align-items-center mb-3">
                                {!! App\Helpers\UserHelper::getAvatarHtml($item->author, 30, 'rounded-circle me-2') !!}
                                <div>
                                    <small class="d-block text-muted">{{ $item->author->name }}</small>
                                    <small class="text-muted">{{ $item->published_at ? $item->published_at->format('d/m/Y') : 'Não publicado' }}</small>
                                </div>
                            </div>
                            <p class="card-text">{{ Str::limit(html_entity_decode(strip_tags($item->content)), 100) }}</p>
                        </div>
                        <div class="card-footer bg-white border-top-0">
                            <a href="{{ route('news.show', $item) }}" class="btn btn-outline-primary w-100">Ler mais</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Paginação -->
        <div class="d-flex justify-content-center mt-4">
            {{ $latestNews->links('vendor.pagination.custom') }}
        </div>

        <!-- Espaço adicional -->
        <div class="mb-5"></div>
    @endif

    <!-- Seja um Repórter Banner -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-light rounded p-4 p-md-5 position-relative overflow-hidden">
                <div class="row">
                    <div class="col-lg-8">
                        <h2 class="mb-3">Seja um Repórter</h2>
                        <p class="lead mb-4">Você é estudante e tem interesse em jornalismo? Junte-se à nossa equipe de jovens repórteres e compartilhe histórias que importam!</p>
                        <a href="#" class="btn btn-primary">Saiba como participar</a>
                    </div>
                    <div class="col-lg-4 d-none d-lg-block">
                        <div class="position-absolute end-0 bottom-0">
                            <i class="bi bi-pen text-primary" style="font-size: 8rem; opacity: 0.2;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap carousel with custom settings
        var myCarousel = document.querySelector('#featuredNewsCarousel');
        var carousel = new bootstrap.Carousel(myCarousel, {
            interval: 5000,
            wrap: true,
            touch: true,
            pause: 'hover'
        });
        
        // Tratamento especial para os botões dentro do carousel
        document.querySelectorAll('#featuredNewsCarousel .carousel-item a.btn').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
            });
            
            button.addEventListener('mouseenter', function() {
                carousel.pause();
            });
            
            button.addEventListener('mouseleave', function() {
                carousel.cycle();
            });
        });

        // Observador de eventos para manter os indicadores sincronizados
        myCarousel.addEventListener('slide.bs.carousel', function(event) {
            const targetIndex = event.to;
            
            // Sincroniza os indicadores mobile e desktop
            document.querySelectorAll('.carousel-indicators.mobile-indicators button').forEach(
                (btn, idx) => btn.classList.toggle('active', idx === targetIndex)
            );
            
            document.querySelectorAll('.carousel-indicators.desktop-indicators button').forEach(
                (btn, idx) => btn.classList.toggle('active', idx === targetIndex)
            );
        });
    });
</script>
@endpush