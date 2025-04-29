@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('news.index') }}" class="text-decoration-none">Notícias</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($news->title, 40) }}</li>
                </ol>
            </nav>

            <!-- Adicione logo após o breadcrumb -->
            @if(!$news->approved)
                <div class="alert alert-warning mb-4">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Esta notícia está aguardando aprovação e ainda não está visível publicamente.
                </div>
            @endif

            <!-- Article Header -->
            <header class="mb-4">
                <h1 class="display-5 fw-bold mb-3">{{ $news->title }}</h1>
                
                <div class="d-flex align-items-center mb-4">
                    @if($news->author->profile_image)
                        <img src="{{ asset('storage/' . $news->author->profile_image) }}" 
                            alt="{{ $news->author->name }}" class="rounded-circle me-3" 
                            width="48" height="48" style="object-fit: cover;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($news->author->name) }}&background=random" 
                            alt="{{ $news->author->name }}" class="rounded-circle me-3" width="48" height="48">
                    @endif
                    
                    <div>
                        <div class="fw-bold">{{ $news->author->name }}</div>
                        <div class="text-muted">
                            @if($news->approved)
                                <span>Publicado em {{ $news->published_at->format('d/m/Y \à\s H:i') }}</span>
                                @if($news->approved_by && $news->author_id !== $news->approved_by)
                                    <span class="ms-2">• Aprovado por {{ $news->approver->name ?? 'Admin' }}</span>
                                @endif
                            @else
                                <span class="text-warning">
                                    <i class="bi bi-clock-history"></i> Aguardando aprovação
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </header>

            <!-- Featured Image -->
            @if($news->image)
                <figure class="figure mb-5 w-100">
                    <img src="{{ asset('storage/' . $news->image) }}" class="figure-img img-fluid rounded shadow-sm w-100" alt="{{ $news->title }}" 
                        style="max-height: 500px; object-fit: cover;">
                    <figcaption class="figure-caption text-end">Imagem: {{ $news->title }}</figcaption>
                </figure>
            @endif

            <!-- Article Content -->
            <div class="news-content">
                {!! $news->content !!}
            </div>

            <!-- Social Share -->
            @php
                $url = urlencode(url()->current()); // Gera o link atual da página codificado
                $title = urlencode($news->title ?? 'Confira esta notícia'); // Corrigido de $noticia->titulo para $news->title
            @endphp

            <!-- Social Share -->
            <div class="d-flex align-items-center py-4 border-top border-bottom mb-5">
                <span class="me-3">Compartilhar:</span>

                <a href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}"
                class="btn btn-sm btn-outline-primary rounded-circle me-2" title="Compartilhar no Facebook" target="_blank">
                    <i class="bi bi-facebook"></i>
                </a>

                <a href="https://wa.me/?text={{ $title }}%20{{ $url }}"
                class="btn btn-sm btn-outline-success rounded-circle me-2" title="Compartilhar no WhatsApp" target="_blank">
                    <i class="bi bi-whatsapp"></i>
                </a>

                <a href="mailto:?subject={{ $title }}&body=Veja%20essa%20notícia:%20{{ $url }}"
                class="btn btn-sm btn-outline-secondary rounded-circle" title="Compartilhar por Email" target="_blank">
                    <i class="bi bi-envelope"></i>
                </a>
            </div>


            <!-- Admin Actions -->
            @auth
                <div class="d-flex justify-content-between mb-5">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Voltar
                    </a>
                    
                    @if(Auth::user()->id === $news->author_id || Auth::user()->isAdmin())
                        <div>
                            <a href="{{ route('news.edit', $news) }}" class="btn btn-primary me-2">
                                <i class="bi bi-pencil-square me-1"></i> Editar
                            </a>
                            
                            <form action="{{ route('news.destroy', $news) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" 
                                       onclick="return confirm('Tem certeza que deseja excluir esta notícia?')">
                                    <i class="bi bi-trash me-1"></i> Excluir
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endauth

            <!-- Últimas Notícias -->
            <div class="latest-news">
                <h3 class="h5 section-title mb-4">Últimas Notícias</h3>
                
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @forelse($latestNews as $latestItem)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                @if($latestItem->image)
                                    <img src="{{ asset('storage/' . $latestItem->image) }}" class="card-img-top" alt="{{ $latestItem->title }}" 
                                         style="height: 150px; object-fit: cover;">
                                @else
                                    <div class="bg-secondary" style="height: 150px;"></div>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ Str::limit($latestItem->title, 40) }}</h5>
                                    <p class="card-text small">{{ Str::limit(html_entity_decode(strip_tags($latestItem->content)), 80) }}</p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <a href="{{ route('news.show', $latestItem) }}" class="btn btn-sm btn-outline-primary">Ler mais</a>
                                        <small class="text-muted">{{ $latestItem->published_at->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">
                                Não há outras notícias publicadas no momento.
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Seleciona todas as imagens dentro do conteúdo da notícia e a imagem destacada
        const contentImages = document.querySelectorAll('.news-content img');
        const featuredImage = document.querySelector('.figure-img');
        
        // Cria modal para visualização de imagens
        const modalHTML = `
            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-xl">
                    <div class="modal-content bg-dark text-white border-0">
                        <div class="modal-header border-0">
                            <h5 class="modal-title" id="imageModalLabel">Visualização da imagem</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                        </div>
                        <div class="modal-body text-center p-0">
                            <img src="" class="img-fluid" id="modalImage" alt="Imagem ampliada">
                            <div class="p-3">
                                <p id="imageCaption" class="small text-light"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Adiciona o HTML da modal ao final do documento
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        
        // Cria a referência do objeto modal
        const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
        
        // Função para abrir a modal com a imagem clicada
        function openImageModal(imgSrc, altText) {
            const modalImage = document.getElementById('modalImage');
            const imageCaption = document.getElementById('imageCaption');
            
            // Define a fonte da imagem na modal
            modalImage.src = imgSrc;
            
            // Define a legenda da imagem na modal
            imageCaption.textContent = altText || 'Imagem da notícia';
            
            // Abre a modal
            imageModal.show();
        }
        
        // Adiciona evento de clique à imagem destacada se existir
        if (featuredImage) {
            featuredImage.style.cursor = 'zoom-in';
            featuredImage.addEventListener('click', function() {
                openImageModal(this.src, this.alt);
            });
        }
        
        // Adiciona evento de clique a todas as imagens do conteúdo
        contentImages.forEach(img => {
            img.style.cursor = 'zoom-in';
            img.addEventListener('click', function() {
                openImageModal(this.src, this.alt);
            });
            
            // Adiciona classe visual de hover para indicar que é clicável
            img.classList.add('img-zoomable');
        });
    });
</script>
@endpush

@push('styles')
<style>
    /* Estilo para indicar imagens clicáveis */
    .img-zoomable {
        transition: all 0.3s ease;
    }
    
    .img-zoomable:hover {
        opacity: 0.9;
        transform: scale(1.02);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2) !important;
    }
    
    /* Estilização para a modal de imagem */
    #imageModal .modal-xl {
        max-width: 90%;
    }
    
    #imageModal .modal-content {
        background-color: rgba(0, 0, 0, 0.9);
    }
    
    #imageModal .modal-body {
        padding: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    #modalImage {
        max-height: 80vh;
        object-fit: contain;
    }
    
    @media (max-width: 768px) {
        #imageModal .modal-xl {
            max-width: 95%;
            margin: 0.5rem;
        }
    }
</style>
@endpush