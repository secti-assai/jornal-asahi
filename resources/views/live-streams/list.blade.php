@extends('layouts.app')

@section('title', 'Transmissões ao Vivo - Jornal Asahi')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="mb-3">Transmissões ao Vivo</h1>
            
            @if($activeLiveStream)
            <div class="alert alert-info">
                <strong><i class="bi bi-broadcast"></i> Ao vivo agora:</strong> 
                <a href="{{ route('home') }}#live-stream" class="alert-link">{{ $activeLiveStream->title }}</a>
            </div>
            @endif
        </div>
    </div>
    
    @if($liveStreams->isEmpty())
    <div class="row">
        <div class="col-12">
            <div class="alert alert-warning">
                Ainda não há transmissões ao vivo registradas.
            </div>
        </div>
    </div>
    @else
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach($liveStreams as $stream)
        <div class="col">
            <div class="card h-100 shadow-sm">
                <div class="ratio ratio-16x9">
                    <img src="https://img.youtube.com/vi/{{ $stream->youtube_video_id }}/mqdefault.jpg" 
                         class="card-img-top" alt="{{ $stream->title }}">
                    
                    @if($stream->is_active)
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-danger"><i class="bi bi-broadcast"></i> AO VIVO</span>
                    </div>
                    @endif
                </div>
                
                <div class="card-body">
                    <h5 class="card-title">{{ $stream->title }}</h5>
                    
                    <p class="card-text small text-muted">
                        <i class="bi bi-calendar-event"></i> 
                        {{ $stream->start_time ? $stream->start_time->format('d/m/Y H:i') : 'Data não definida' }}
                    </p>
                    
                    <p class="card-text">{{ \Illuminate\Support\Str::limit($stream->description, 100) }}</p>
                    
                    <a href="https://www.youtube.com/watch?v={{ $stream->youtube_video_id }}" 
                       target="_blank" class="btn btn-primary btn-sm">
                        <i class="bi bi-play-fill"></i> Assistir no YouTube
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $liveStreams->links() }}
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush