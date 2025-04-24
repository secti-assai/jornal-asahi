@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Dashboard</h1>
    
    <div>
        @if(Auth::user()->isReporter() || Auth::user()->isAdmin())
            <a href="{{ route('news.create') }}" class="btn btn-primary">Nova Notícia</a>
        @endif
        
        @if(Auth::user()->isAdmin())
            <a href="{{ route('users.index') }}" class="btn btn-success">Gerenciar Usuários</a>
        @endif
    </div>
</div>

@if(Auth::user()->isReporter())
    <h2 class="h4 mb-3">Minhas Notícias</h2>
@elseif(Auth::user()->isApprover())
    <h2 class="h4 mb-3">Notícias Pendentes de Aprovação</h2>
@else
    <h2 class="h4 mb-3">Todas as Notícias</h2>
@endif

@if($news->count() > 0)
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Status</th>
                    <th>Data</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($news as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->author->name }}</td>
                        <td>
                            @if($item->approved)
                                <span class="badge bg-success">Aprovada</span>
                            @else
                                <span class="badge bg-warning text-dark">Pendente</span>
                            @endif
                        </td>
                        <td>{{ $item->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('news.show', $item) }}" class="btn btn-sm btn-info">Ver</a>
                            
                            @if((Auth::user()->id === $item->author_id || Auth::user()->isAdmin()) && !$item->approved)
                                <a href="{{ route('news.edit', $item) }}" class="btn btn-sm btn-primary">Editar</a>
                            @endif
                            
                            @if((Auth::user()->isApprover() || Auth::user()->isAdmin()) && !$item->approved)
                                <form action="{{ route('news.approve', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success">Aprovar</button>
                                </form>
                            @endif
                            
                            @if(Auth::user()->id === $item->author_id || Auth::user()->isAdmin())
                                <form action="{{ route('news.destroy', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza?')">Excluir</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $news->links() }}
    </div>
@else
    <div class="alert alert-info">
        Não há notícias disponíveis.
    </div>
@endif
@endsection