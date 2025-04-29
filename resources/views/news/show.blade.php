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
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($news->author->name) }}&background=random" 
                        alt="{{ $news->author->name }}" class="rounded-circle me-3" width="48" height="48">
                    
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
            <div class="news-content mb-5">
                <div class="fs-5 lh-lg">
                    {!! $news->content !!}
                </div>
            </div>

            <!-- Social Share -->
            @php
                $url = urlencode(url()->current()); // Gera o link atual da página codificado
                $title = urlencode($noticia->titulo ?? 'Confira esta notícia'); // ou outro campo do modelo
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
                                    <p class="card-text small">{{ Str::limit(strip_tags($latestItem->content), 80) }}</p>
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