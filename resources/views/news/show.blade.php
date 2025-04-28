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

            <!-- Article Header -->
            <header class="mb-4">
                <h1 class="display-5 fw-bold mb-3">{{ $news->title }}</h1>
                
                <div class="d-flex align-items-center mb-4">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($news->author->name) }}&background=random" 
                        alt="{{ $news->author->name }}" class="rounded-circle me-3" width="48" height="48">
                    
                    <div>
                        <div class="fw-bold">{{ $news->author->name }}</div>
                        <div class="text-muted">
                            <span>Publicado em {{ $news->published_at->format('d/m/Y \à\s H:i') }}</span>
                            @if($news->author_id !== $news->approved_by)
                                <span class="ms-2">• Aprovado por {{ $news->approver->name ?? 'Admin' }}</span>
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
            <div class="d-flex align-items-center py-4 border-top border-bottom mb-5">
                <span class="me-3">Compartilhar:</span>
                <a href="#" class="btn btn-sm btn-outline-primary rounded-circle me-2" title="Compartilhar no Facebook">
                    <i class="bi bi-facebook"></i>
                </a>
                <a href="#" class="btn btn-sm btn-outline-info rounded-circle me-2" title="Compartilhar no Twitter">
                    <i class="bi bi-twitter-x"></i>
                </a>
                <a href="#" class="btn btn-sm btn-outline-success rounded-circle me-2" title="Compartilhar no WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                </a>
                <a href="#" class="btn btn-sm btn-outline-secondary rounded-circle" title="Compartilhar por Email">
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
            
            <!-- Related News -->
            <div class="related-news">
                <h3 class="h5 section-title mb-4">Notícias Relacionadas</h3>
                
                <div class="row row-cols-1 row-cols-md-3 g-4">
                    @for ($i = 0; $i < 3; $i++)
                        <div class="col">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="bg-secondary" style="height: 150px;"></div>
                                <div class="card-body">
                                    <h5 class="card-title">Notícia relacionada exemplo</h5>
                                    <p class="card-text small">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                                    <a href="#" class="btn btn-sm btn-outline-primary">Ler mais</a>
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>
@endsection