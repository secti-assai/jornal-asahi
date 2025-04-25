@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Dashboard</h1>
    
    <div>
        @if(Auth::user()->isReporter() || Auth::user()->isApprover() || Auth::user()->isAdmin())
            <a href="{{ route('news.create') }}" class="btn btn-primary">Nova Notícia</a>
        @endif
        
        @if(Auth::user()->isAdmin())
            <a href="{{ route('users.index') }}" class="btn btn-success">Gerenciar Usuários</a>
        @endif
    </div>
</div>

@if(isset($myNews))
    <h2 class="h4 mb-3">Minhas Notícias</h2>
    @if($myNews->count() > 0)
        <!-- Tabela de minhas notícias -->
        @include('dashboard._news_table', ['news' => $myNews])
        <div class="d-flex justify-content-center mt-4">
            {{ $myNews->appends(['pending_news' => request()->get('pending_news'), 'all_news' => request()->get('all_news')])->links() }}
        </div>
    @else
        <div class="alert alert-info">
            Você ainda não criou nenhuma notícia.
        </div>
    @endif
@endif

@if(isset($pendingNews))
    <h2 class="h4 mb-3 mt-5">Notícias Pendentes de Aprovação</h2>
    @if($pendingNews->count() > 0)
        <!-- Tabela de notícias pendentes -->
        @include('dashboard._news_table', ['news' => $pendingNews])
        <div class="d-flex justify-content-center mt-4">
            {{ $pendingNews->appends(['my_news' => request()->get('my_news'), 'all_news' => request()->get('all_news')])->links() }}
        </div>
    @else
        <div class="alert alert-info">
            Não há notícias pendentes de aprovação.
        </div>
    @endif
@endif

@if(isset($allNews) && Auth::user()->isAdmin())
    <h2 class="h4 mb-3 mt-5">Todas as Notícias</h2>
    @if($allNews->count() > 0)
        <!-- Tabela de todas as notícias -->
        @include('dashboard._news_table', ['news' => $allNews])
        <div class="d-flex justify-content-center mt-4">
            {{ $allNews->appends(['my_news' => request()->get('my_news'), 'pending_news' => request()->get('pending_news')])->links() }}
        </div>
    @else
        <div class="alert alert-info">
            Não há notícias cadastradas.
        </div>
    @endif
@endif
@endsection