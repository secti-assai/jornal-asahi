@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Dashboard</h1>
    
    <div>
        @if(Auth::user()->isReporter() || Auth::user()->isApprover() || Auth::user()->isAdmin())
            <a href="{{ route('news.create') }}" class="btn btn-primary">Nova Notícia</a>
        @endif
        
        @if(Auth::user()->isAdmin())
            <a href="{{ url('/admin/users') }}" class="btn btn-success">Gerenciar Usuários</a>
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

@if(isset($liveStreams) && Auth::user()->isAdmin())
<div class="mt-5">
    <h2 class="h4 mb-3">Gerenciar Transmissões ao Vivo</h2>
    
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#liveStreamModal">
        Nova Transmissão
    </button>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>ID do YouTube</th>
                    <th>Status</th>
                    <th>Data de Início</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach($liveStreams as $stream)
                <tr>
                    <td>{{ $stream->title }}</td>
                    <td>{{ $stream->youtube_video_id }}</td>
                    <td>
                        @if($stream->is_active)
                            <span class="badge bg-success">Ativa</span>
                        @else
                            <span class="badge bg-secondary">Inativa</span>
                        @endif
                    </td>
                    <td>{{ $stream->start_time ? $stream->start_time->format('d/m/Y H:i') : 'Não definido' }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="#" class="btn btn-sm btn-primary edit-stream" 
                               data-id="{{ $stream->id }}" data-bs-toggle="modal" data-bs-target="#liveStreamModal">
                                Editar
                            </a>
                            
                            @if(!$stream->is_active)
                            <form action="{{ url('/admin/live-streams/'.$stream->id.'/activate') }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-sm btn-success">Ativar</button>
                            </form>
                            @endif
                            
                            <form action="{{ url('/admin/live-streams/'.$stream->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" 
                                        onclick="return confirm('Tem certeza que deseja excluir esta transmissão?')">
                                    Excluir
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach

                @if($liveStreams->count() == 0)
                <tr>
                    <td colspan="5" class="text-center">Nenhuma transmissão ao vivo cadastrada.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Live Stream Modal -->
<div class="modal fade" id="liveStreamModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nova Transmissão ao Vivo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="liveStreamForm" action="{{ url('/admin/live-streams') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="method" value="POST">
                <input type="hidden" name="stream_id" id="stream_id" value="">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="youtube_video_id" class="form-label">ID do Vídeo YouTube</label>
                        <input type="text" class="form-control" id="youtube_video_id" name="youtube_video_id" required>
                        <div class="form-text">Ex: dQw4w9WgXcQ (encontrado na URL: youtube.com/watch?v=dQw4w9WgXcQ)</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Título</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="start_time" class="form-label">Data/Hora de Início</label>
                        <input type="datetime-local" class="form-control" id="start_time" name="start_time">
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1">
                        <label class="form-check-label" for="is_active">
                            Ativar esta transmissão (desativa qualquer outra ativa)
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle edit button clicks for live streams
        const editButtons = document.querySelectorAll('.edit-stream');
        if (editButtons.length > 0) {
            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const streamId = this.getAttribute('data-id');
                    
                    // Fetch stream data via API usando URL direta
                    fetch(`/admin/live-streams/${streamId}`)
                        .then(response => response.json())
                        .then(stream => {
                            document.getElementById('modalTitle').textContent = 'Editar Transmissão ao Vivo';
                            document.getElementById('method').value = 'PUT';
                            document.getElementById('stream_id').value = stream.id;
                            document.getElementById('youtube_video_id').value = stream.youtube_video_id;
                            document.getElementById('title').value = stream.title;
                            document.getElementById('description').value = stream.description || '';
                            
                            if (stream.start_time) {
                                const date = new Date(stream.start_time);
                                const formattedDate = date.toISOString().slice(0, 16);
                                document.getElementById('start_time').value = formattedDate;
                            } else {
                                document.getElementById('start_time').value = '';
                            }
                            
                            document.getElementById('is_active').checked = stream.is_active;
                            
                            // Update form action URL usando URL direta
                            document.getElementById('liveStreamForm').action = `/admin/live-streams/${stream.id}`;
                        })
                        .catch(error => {
                            console.error('Error fetching live stream data:', error);
                            alert('Erro ao carregar dados da transmissão');
                        });
                });
            });
        }
        
        // Reset form when creating new live stream
        const newButton = document.querySelector('button[data-bs-target="#liveStreamModal"]');
        if (newButton) {
            newButton.addEventListener('click', function() {
                if (!this.classList.contains('edit-stream')) {
                    document.getElementById('modalTitle').textContent = 'Nova Transmissão ao Vivo';
                    document.getElementById('liveStreamForm').reset();
                    document.getElementById('method').value = 'POST';
                    document.getElementById('stream_id').value = '';
                    document.getElementById('liveStreamForm').action = '/admin/live-streams';
                }
            });
        }
    });
</script>
@endpush
@endsection