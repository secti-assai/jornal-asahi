@extends('layouts.app')

@section('content')
<h1 class="mb-4">Últimas Notícias</h1>

@if($news->count() > 0)
    <div class="row">
        @foreach($news as $item)
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->title }}">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->title }}</h5>
                        <p class="card-text text-muted">
                            <small>Por {{ $item->author->name }} em {{ $item->published_at->format('d/m/Y H:i') }}</small>
                        </p>
                        <p class="card-text">{{ Str::limit(strip_tags($item->content), 150) }}</p>
                        <a href="{{ route('news.show', $item) }}" class="btn btn-primary">Ler mais</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $news->links() }}
    </div>
@else
    <div class="alert alert-info">
        Não há notícias disponíveis no momento.
    </div>
@endif
@endsection