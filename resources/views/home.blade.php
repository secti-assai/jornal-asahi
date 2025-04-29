@extends('layouts.app')

@section('content')
<div class="container py-3">
    <!-- Primeira linha: Notícias em Destaques e Ao Vivo lado a lado -->
    <div class="row mb-4">
        <!-- Notícias em Destaques -->
        <div class="col-lg-8 mb-4 mb-lg-0">
            @if($featuredNews && $featuredNews->count() > 0)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h2 class="section-title h4 mb-1">Notícias em Destaques</h2>
                        <p class="text-muted small mb-0">Mantenha-se informado com as últimas notícias</p>
                    </div>
                    <a href="{{ route('news.index') }}" class="text-decoration-none text-primary d-none d-md-block small">
                        Ver todas <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                
                <div id="featuredNewsCarousel" class="carousel slide rounded overflow-hidden shadow-sm" data-bs-ride="carousel">
                    <!-- Indicadores simplificados -->
                    <div class="carousel-indicators" style="margin-bottom: 0.5rem;">
                        @foreach($featuredNews as $index => $item)
                            <button type="button" data-bs-target="#featuredNewsCarousel" data-bs-slide-to="{{ $index }}" 
                                class="{{ $index == 0 ? 'active' : '' }}" aria-current="{{ $index == 0 ? 'true' : 'false' }}" 
                                aria-label="Slide {{ $index + 1 }}"></button>
                        @endforeach
                    </div>

                    <div class="carousel-inner">
                        @foreach($featuredNews as $index => $item)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}" data-bs-interval="6000">
                                <div class="position-relative">
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" class="d-block w-100" alt="{{ $item->title }}" 
                                            style="height: 350px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary d-block w-100" style="height: 350px; display: flex; align-items: center; justify-content: center;">
                                            <span class="text-white">Sem imagem disponível</span>
                                        </div>
                                    @endif
                                    <div class="position-absolute bottom-0 start-0 w-100 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.8)); pointer-events: none;">
                                        <h3 class="text-white h5 mb-2">{{ $item->title }}</h3>
                                        <p class="text-white-50 small mb-2 d-none d-sm-block">{{ Str::limit(html_entity_decode(strip_tags($item->content)), 100) }}</p>
                                        <a href="{{ route('news.show', $item) }}" class="btn btn-sm btn-primary" style="pointer-events: auto; position: relative; z-index: 100;">Ler matéria</a>
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
                
                <!-- Link para todas as notícias em dispositivos móveis -->
                <div class="d-flex justify-content-center mt-2 d-md-none">
                    <a href="{{ route('news.index') }}" class="btn btn-sm btn-outline-primary">
                        Ver todas as notícias
                    </a>
                </div>
            @endif
        </div>

        <!-- Transmissão ao Vivo -->
        <div class="col-lg-4">
            @if(isset($activeLiveStream) && $activeLiveStream)
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h2 class="section-title h4 mb-1">
                            <i class="bi bi-broadcast live-indicator me-1"></i>
                            Ao Vivo
                        </h2>
                        <p class="text-muted small mb-0">Acompanhe nossas transmissões em tempo real</p>
                    </div>
                    <a href="#" class="text-decoration-none text-primary d-none d-md-block small">
                        Mais <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                
                <div class="card shadow-sm border-0 overflow-hidden mt-2 live-stream-card">
                    <div class="ratio ratio-16x9 live-stream-container">
                        <iframe 
                            src="https://www.youtube.com/embed/{{ $activeLiveStream->youtube_video_id }}?autoplay=0&rel=0" 
                            title="{{ $activeLiveStream->title }}" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                    </div>
                    <div class="card-body p-2 p-md-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <h3 class="card-title h6 mb-0">{{ Str::limit($activeLiveStream->title, 40) }}</h3>
                            <span class="badge bg-danger"><i class="bi bi-circle-fill me-1"></i> AO VIVO</span>
                        </div>
                        <div class="d-flex align-items-center mb-2 small">
                            <i class="bi bi-calendar-event me-1"></i>
                            <small class="text-muted">{{ date('d/m/Y', strtotime($activeLiveStream->start_time)) }}</small>
                        </div>
                        <a href="https://www.youtube.com/watch?v={{ $activeLiveStream->youtube_video_id }}" 
                           class="btn btn-sm btn-danger w-100" target="_blank">
                            <i class="bi bi-youtube me-1"></i> Assistir no YouTube
                        </a>
                    </div>
                </div>
            @else
                <!-- Opcional: Conteúdo alternativo quando não há transmissão ao vivo -->
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <h2 class="section-title h4 mb-1">
                            <i class="bi bi-broadcast me-1"></i>
                            Transmissões
                        </h2>
                        <p class="text-muted small mb-0">Acompanhe nossas transmissões quando disponíveis</p>
                    </div>
                </div>
                <div class="card shadow-sm border-0 overflow-hidden mt-2 bg-light live-stream-card">
                    <div class="card-body p-4 text-center">
                        <i class="bi bi-camera-video-off text-muted mb-3" style="font-size: 2rem;"></i>
                        <h3 class="h6 mb-2">Nenhuma transmissão ao vivo no momento</h3>
                        <p class="small text-muted">Fique de olho para novas transmissões em breve.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Nova seção: Galeria de Imagens -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h2 class="section-title h4 mb-1">
                        <i class="bi bi-images me-1"></i>
                        Galeria
                    </h2>
                    <p class="text-muted small mb-0">Visualize todas as imagens das notícias em um só lugar</p>
                </div>
                <a href="{{ route('gallery.index') }}" class="text-decoration-none text-primary d-none d-md-block small">
                    Ver todas <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            
            <div class="row row-cols-2 row-cols-md-4 row-cols-lg-6 g-2">
                @if(isset($galleryImages) && $galleryImages->count() > 0)
                    @foreach($galleryImages as $image)
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0 gallery-card">
                                <img src="{{ asset('storage/' . $image->path) }}" 
                                     class="gallery-img" 
                                     alt="{{ $image->news->title ?? 'Imagem da notícia' }}"
                                     data-bs-toggle="modal"
                                     data-bs-target="#galleryModal"
                                     data-img-src="{{ asset('storage/' . $image->path) }}"
                                     data-news-title="{{ $image->news->title ?? 'Sem título' }}"
                                     data-news-url="{{ route('news.show', $image->news_id) }}">
                                <div class="card-img-overlay d-flex flex-column justify-content-end p-2">
                                    <h6 class="card-title m-0 text-white small">{{ Str::limit($image->news->title ?? 'Sem título', 30) }}</h6>
                                    <a href="{{ route('news.show', $image->news_id) }}" class="stretched-link" aria-hidden="true"></a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="alert alert-light text-center">
                            Ainda não há imagens na galeria.
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Link para galeria completa - APENAS para mobile -->
            <div class="d-flex justify-content-center mt-3 d-md-none">
                <a href="{{ route('gallery.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-images me-1"></i> Ver galeria completa
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal da Galeria -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 bg-dark text-white">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="galleryModalLabel">Imagem da notícia</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="galleryModalImage" src="" class="img-fluid" alt="Imagem ampliada">
            </div>
            <div class="modal-footer border-0">
                <a id="galleryModalNewsLink" href="#" class="btn btn-primary">
                    Ir para a notícia <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Estilos para a galeria de imagens */
    .gallery-card {
        cursor: pointer;
        transition: all 0.3s ease;
        overflow: hidden;
        border-radius: 6px;
    }
    
    .gallery-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    
    .gallery-img {
        height: 120px;
        width: 100%;
        object-fit: cover;
        transition: all 0.5s ease;
    }
    
    .gallery-card:hover .gallery-img {
        transform: scale(1.05);
    }
    
    .card-img-overlay {
        background: linear-gradient(transparent, rgba(0,0,0,0.7));
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .gallery-card:hover .card-img-overlay {
        opacity: 1;
    }
    
    /* Em dispositivos móveis, sempre mostrar a sobreposição para melhor UX */
    @media (max-width: 767px) {
        .card-img-overlay {
            opacity: 1;
            padding: 8px;
        }
        
        .gallery-img {
            height: 100px; /* Imagens um pouco menores em dispositivos móveis */
        }
    }
    
    #galleryModalImage {
        max-height: 70vh;
        object-fit: contain;
    }
    
    /* Estilos para limitar o tamanho do box de transmissão ao vivo */
    .live-stream-card {
        max-height: 350px;
        display: flex;
        flex-direction: column;
    }
    
    .live-stream-container {
        height: 0;
        padding-bottom: 56.25%; /* Proporção 16:9 */
        max-height: 200px;
    }
    
    .live-stream-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    
    @media (max-width: 991px) {
        .live-stream-card {
            max-height: none;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa o carousel
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
        
        // Funcionalidade da galeria
        var galleryModal = document.getElementById('galleryModal');
        galleryModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var imgSrc = button.getAttribute('data-img-src');
            var newsTitle = button.getAttribute('data-news-title');
            var newsUrl = button.getAttribute('data-news-url');
            
            var modalImage = document.getElementById('galleryModalImage');
            var modalTitle = document.getElementById('galleryModalLabel');
            var modalLink = document.getElementById('galleryModalNewsLink');
            
            modalImage.src = imgSrc;
            modalTitle.textContent = newsTitle;
            modalLink.href = newsUrl;
        });
        
        // Tratamento especial para dispositivos touch/mobile
        if ('ontouchstart' in window) {
            document.querySelectorAll('.gallery-card').forEach(function(card) {
                card.classList.add('touch-device');
            });
        }
    });
</script>
@endpush