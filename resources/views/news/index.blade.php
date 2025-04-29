@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-between align-items-center mb-4">
        <div class="col-lg-6">
            <h1 class="section-title mb-3 mb-lg-0">Últimas Notícias</h1>
        </div>
        <div class="col-lg-4">
            <form action="{{ route('news.index') }}" method="GET" class="search-form">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Buscar notícias..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Mostrar resultados da pesquisa, se houver -->
    @if(request('search'))
        <div class="alert alert-info">
            Resultados da pesquisa para: <strong>{{ request('search') }}</strong>
            <a href="{{ route('news.index') }}" class="float-end">Limpar pesquisa</a>
        </div>
    @endif
    
    <!-- Restante do código da view para exibir as notícias -->
    @if($news->count() > 0)
        <div class="row mb-5">
            @foreach($news as $index => $item)
                @if($index === 0)
                    <!-- Notícia em destaque -->
                    <div class="col-12 mb-4">
                        <div class="card border-0 shadow-sm overflow-hidden">
                            <div class="row g-0">
                                <div class="col-md-6">
                                    @if($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid h-100 w-100" 
                                            alt="{{ $item->title }}" style="object-fit: cover;">
                                    @else
                                        <div class="bg-secondary h-100 d-flex align-items-center justify-content-center">
                                            <i class="bi bi-image text-white" style="font-size: 5rem;"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6">
                                    <div class="card-body d-flex flex-column h-100">
                                        <div class="mb-2">
                                            <span class="badge bg-primary">Em Destaque</span>
                                        </div>
                                        <h3 class="card-title mb-3">{{ $item->title }}</h3>
                                        <div class="d-flex align-items-center mb-3">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($item->author->name) }}&background=random" 
                                                class="rounded-circle me-2" alt="{{ $item->author->name }}" width="30" height="30">
                                            <div>
                                                <small class="d-block">{{ $item->author->name }}</small>
                                                <small class="text-muted">{{ $item->published_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                        </div>
                                        <p class="card-text flex-grow-1">{{ Str::limit(strip_tags($item->content), 300) }}</p>
                                        <a href="{{ route('news.show', $item) }}" class="btn btn-primary mt-auto">
                                            Ler mais <i class="bi bi-arrow-right ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Notícias regulares -->
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 border-0 shadow-sm">
                            @if($item->image)
                                <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" 
                                    alt="{{ $item->title }}" style="height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-light text-center p-4">
                                    <i class="bi bi-image text-secondary" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title mb-3">{{ $item->title }}</h5>
                                <div class="d-flex align-items-center mb-3">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($item->author->name) }}&background=random" 
                                        class="rounded-circle me-2" alt="{{ $item->author->name }}" width="24" height="24">
                                    <div>
                                        <small class="d-block text-muted">{{ $item->author->name }}</small>
                                        <small class="text-muted">{{ $item->published_at->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                                <p class="card-text">{{ Str::limit(strip_tags($item->content), 100) }}</p>
                            </div>
                            <div class="card-footer bg-white border-top-0">
                                <a href="{{ route('news.show', $item) }}" class="btn btn-outline-primary btn-sm">
                                    Ler mais <i class="bi bi-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Paginação -->
        <div class="d-flex justify-content-center">
            {{ $news->links() }}
        </div>
    @else
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i> Não há notícias disponíveis no momento.
        </div>
    @endif
</div>
@endsection