@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <h1 class="mb-3">{{ $news->title }}</h1>
        
        <p class="text-muted">
            Por {{ $news->author->name }} em {{ $news->published_at->format('d/m/Y H:i') }}
        </p>
        
        @if($news->image)
            <img src="{{ asset('storage/' . $news->image) }}" class="img-fluid mb-4" alt="{{ $news->title }}">
        @endif
        
        <div class="news-content">
            {!! $news->content !!}
        </div>
        
        @auth
            <div class="mt-5">
                <a href="{{ route('home') }}" class="btn btn-secondary">Voltar para a home</a>
                
                @if(Auth::user()->id === $news->author_id || Auth::user()->isAdmin())
                    <a href="{{ route('news.edit', $news) }}" class="btn btn-primary">Editar</a>
                    
                    <form action="{{ route('news.destroy', $news) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta notícia?')">Excluir</button>
                    </form>
                @endif
            </div>
        @endauth
    </div>
</div>
@endsection