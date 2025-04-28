@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <h1 class="section-title mb-3 mb-md-0">Dashboard</h1>
                
                <div class="d-flex flex-wrap gap-2">
                    @if(Auth::user()->isReporter() || Auth::user()->isApprover() || Auth::user()->isAdmin())
                        <a href="{{ route('news.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-lg me-1"></i> Nova Notícia
                        </a>
                    @endif
                    
                    @if(Auth::user()->isAdmin())
                        <a href="{{ url('/admin/users') }}" class="btn btn-success">
                            <i class="bi bi-people me-1"></i> Gerenciar Usuários
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">
                            <i class="bi bi-newspaper text-primary fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Minhas Notícias</h6>
                            <h4 class="mb-0">{{ isset($myNews) ? $myNews->total() : 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(Auth::user()->isApprover() || Auth::user()->isAdmin())
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                            <i class="bi bi-clock-history text-warning fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Pendentes</h6>
                            <h4 class="mb-0">{{ isset($pendingNews) ? $pendingNews->total() : 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(Auth::user()->isAdmin())
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Publicadas</h6>
                            <h4 class="mb-0">{{ isset($allNews) ? $allNews->where('approved', true)->count() : 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                            <i class="bi bi-broadcast text-info fs-4"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Transmissões</h6>
                            <h4 class="mb-0">{{ isset($liveStreams) ? $liveStreams->count() : 0 }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Minhas Notícias -->
    @if(isset($myNews))
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h4 class="card-title mb-0">Minhas Notícias</h4>
        </div>
        <div class="card-body">
            @if($myNews->count() > 0)
                @include('dashboard._news_table', ['news' => $myNews])
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Navegação de páginas">
                        <ul class="pagination pagination-md flex-wrap justify-content-center">
                            {{-- Link "Anterior" --}}
                            @if ($myNews->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&laquo;</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $myNews->previousPageUrl() }}" aria-label="Anterior">&laquo;</a>
                                </li>
                            @endif

                            {{-- Links numerados --}}
                            @for ($i = 1; $i <= $myNews->lastPage(); $i++)
                                <li class="page-item {{ ($i == $myNews->currentPage()) ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $myNews->appends(['pending_news' => request()->get('pending_news'), 'all_news' => request()->get('all_news')])->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- Link "Próximo" --}}
                            @if ($myNews->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $myNews->nextPageUrl() }}" aria-label="Próximo">&raquo;</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&raquo;</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i> Você ainda não criou nenhuma notícia.
                    <a href="{{ route('news.create') }}" class="alert-link">Criar agora</a>.
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Notícias Pendentes -->
    @if(isset($pendingNews))
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h4 class="card-title mb-0">Notícias Pendentes de Aprovação</h4>
        </div>
        <div class="card-body">
            @if($pendingNews->count() > 0)
                @include('dashboard._news_table', ['news' => $pendingNews])
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Navegação de páginas">
                        <ul class="pagination pagination-md flex-wrap justify-content-center">
                            @if ($pendingNews->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&laquo;</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $pendingNews->previousPageUrl() }}" aria-label="Anterior">&laquo;</a>
                                </li>
                            @endif

                            @for ($i = 1; $i <= $pendingNews->lastPage(); $i++)
                                <li class="page-item {{ ($i == $pendingNews->currentPage()) ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $pendingNews->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if ($pendingNews->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $pendingNews->nextPageUrl() }}" aria-label="Próximo">&raquo;</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&raquo;</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i> Não há notícias pendentes de aprovação.
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Todas as Notícias -->
    @if(isset($allNews) && Auth::user()->isAdmin())
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h4 class="card-title mb-0">Todas as Notícias</h4>
        </div>
        <div class="card-body">
            @if($allNews->count() > 0)
                @include('dashboard._news_table', ['news' => $allNews])
                <div class="d-flex justify-content-center mt-4">
                    <nav aria-label="Navegação de páginas">
                        <ul class="pagination pagination-md flex-wrap justify-content-center">
                            @if ($allNews->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&laquo;</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $allNews->previousPageUrl() }}" aria-label="Anterior">&laquo;</a>
                                </li>
                            @endif

                            @for ($i = 1; $i <= $allNews->lastPage(); $i++)
                                <li class="page-item {{ ($i == $allNews->currentPage()) ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $allNews->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            @if ($allNews->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $allNews->nextPageUrl() }}" aria-label="Próximo">&raquo;</a>
                                </li>
                            @else
                                <li class="page-item disabled">
                                    <span class="page-link" aria-hidden="true">&raquo;</span>
                                </li>
                            @endif
                        </ul>
                    </nav>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i> Não há notícias cadastradas.
                </div>
            @endif
        </div>
    </div>
    @endif

    <!-- Gerenciar transmissões ao vivo para admins -->
    @if(isset($liveStreams) && Auth::user()->isAdmin())
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h4 class="card-title mb-0">Gerenciar Transmissões ao Vivo</h4>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#liveStreamModal">
                <i class="bi bi-plus-lg me-1"></i> Nova Transmissão
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Título</th>
                            <th>ID do YouTube</th>
                            <th>Status</th>
                            <th>Data de Início</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($liveStreams->count() > 0)
                            @foreach($liveStreams as $stream)
                            <tr>
                                <td>{{ $stream->title }}</td>
                                <td><code>{{ $stream->youtube_video_id }}</code></td>
                                <td>
                                    @if($stream->is_active)
                                        <span class="badge bg-success">Ativa</span>
                                    @else
                                        <span class="badge bg-secondary">Inativa</span>
                                    @endif
                                </td>
                                <td>{{ $stream->start_time ? $stream->start_time->format('d/m/Y H:i') : 'Não definido' }}</td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="#" class="btn btn-primary edit-stream" 
                                           data-id="{{ $stream->id }}" data-bs-toggle="modal" data-bs-target="#liveStreamModal">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        
                                        @if(!$stream->is_active)
                                        <form action="{{ url('/admin/live-streams/'.$stream->id.'/activate') }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-success">
                                                <i class="bi bi-broadcast"></i>
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <form action="{{ url('/admin/live-streams/'.$stream->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" 
                                                    onclick="return confirm('Tem certeza que deseja excluir esta transmissão?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <i class="bi bi-broadcast display-4 d-block mb-3 text-muted"></i>
                                    <p class="mb-0">Nenhuma transmissão ao vivo cadastrada.</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para transmissões ao vivo -->
    <div class="modal fade" id="liveStreamModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
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
                        
                        <div class="form-check form-switch mb-3">
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
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle edit button clicks for live streams
        const editButtons = document.querySelectorAll('.edit-stream');
        if (editButtons.length > 0) {
            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const streamId = this.getAttribute('data-id');
                    
                    // Fetch stream data via API
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
        const newButton = document.querySelector('button[data-bs-target="#liveStreamModal"]:not(.edit-stream)');
        if (newButton) {
            newButton.addEventListener('click', function() {
                document.getElementById('modalTitle').textContent = 'Nova Transmissão ao Vivo';
                document.getElementById('liveStreamForm').reset();
                document.getElementById('method').value = 'POST';
                document.getElementById('stream_id').value = '';
                document.getElementById('liveStreamForm').action = '/admin/live-streams';
            });
        }
    });
</script>
@endpush
@endsection