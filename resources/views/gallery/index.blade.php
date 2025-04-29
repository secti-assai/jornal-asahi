@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">Galeria de Imagens</h1>
    
    <div class="row">
        <div class="col-12 mb-4">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Galeria</li>
                </ol>
            </nav>
        </div>
    </div>
    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('gallery.index') }}" method="GET" class="row g-3">
                        <div class="col-md-6">
                            <label for="type" class="form-label">Tipo de imagem</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">Todas as imagens</option>
                                <option value="cover" {{ request('type') == 'cover' ? 'selected' : '' }}>Imagens de capa</option>
                                <option value="content" {{ request('type') == 'content' ? 'selected' : '' }}>Imagens do conteúdo</option>
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                            @if(request()->has('type') || request()->has('news_id'))
                                <a href="{{ route('gallery.index') }}" class="btn btn-outline-secondary ms-2">Limpar filtros</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3">
        @forelse($images as $image)
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
                    <div class="card-img-overlay d-flex flex-column justify-content-end">
                        <h6 class="card-title text-white mb-0">
                            {{ Str::limit($image->news->title ?? 'Sem título', 100) }}
                        </h6>
                        <p class="card-text text-white-50 small">
                            {{ $image->is_cover ? 'Imagem de capa' : 'Imagem do conteúdo' }}
                        </p>
                        <a href="{{ route('news.show', $image->news_id) }}" 
                           class="btn btn-sm btn-primary mt-2">
                            Ver notícia
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="bi bi-info-circle me-2"></i>
                    Ainda não há imagens na galeria.
                </div>
            </div>
        @endforelse
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $images->links() }}
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
            <div class="modal-footer border-0 d-flex justify-content-between">
                <span id="galleryImageType" class="text-white-50"></span>
                <a id="galleryModalNewsLink" href="#" class="btn btn-primary">
                    Ir para a notícia <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var galleryModal = document.getElementById('galleryModal');
        galleryModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget;
            var imgSrc = button.getAttribute('data-img-src');
            var newsTitle = button.getAttribute('data-news-title');
            var newsUrl = button.getAttribute('data-news-url');
            var imageType = button.closest('.card').querySelector('.card-text').textContent.trim();
            
            var modalImage = document.getElementById('galleryModalImage');
            var modalTitle = document.getElementById('galleryModalLabel');
            var modalLink = document.getElementById('galleryModalNewsLink');
            var modalType = document.getElementById('galleryImageType');
            
            modalImage.src = imgSrc;
            modalTitle.textContent = newsTitle;
            modalLink.href = newsUrl;
            modalType.textContent = imageType;
        });
    });
</script>
@endpush

@push('styles')
<style>
    .gallery-card {
        overflow: hidden;
        position: relative;
    }
    
    .gallery-img {
        height: 200px;
        width: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .gallery-card:hover .gallery-img {
        transform: scale(1.05);
    }
    
    .card-img-overlay {
        background: linear-gradient(transparent, rgba(0,0,0,0.8));
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .gallery-card:hover .card-img-overlay {
        opacity: 1;
    }
    
    #galleryModalImage {
        max-height: 70vh;
        object-fit: contain;
    }
</style>
@endpush