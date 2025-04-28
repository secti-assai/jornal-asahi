<div class="table-responsive">
    <table class="table table-hover">
        <thead class="table-light">
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
                    <td class="text-truncate" style="max-width: 300px;">{{ $item->title }}</td>
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
                        <div class="btn-group btn-group-sm">
                            <a href="{{ route('news.show', $item) }}" class="btn btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            
                            @if((Auth::user()->id === $item->author_id || Auth::user()->isAdmin()) && !$item->approved)
                                <a href="{{ route('news.edit', $item) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            @endif
                            
                            @if((Auth::user()->isApprover() || Auth::user()->isAdmin()) && !$item->approved)
                                <form action="{{ route('news.approve', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                            @endif
                            
                            @if(Auth::user()->id === $item->author_id || Auth::user()->isAdmin())
                                <form action="{{ route('news.destroy', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Tem certeza?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>