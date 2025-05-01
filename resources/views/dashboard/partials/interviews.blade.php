@if(isset($interviews) && Auth::user()->isAdmin())
<div class="card shadow-sm mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0"><i class="bi bi-mic"></i> Entrevistas</h5>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addInterviewModal">
            <i class="bi bi-plus-lg"></i> Nova Entrevista
        </button>
    </div>
    <div class="card-body">
        @if($interviews->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Título</th>
                            <th>Entrevistado</th>
                            <th>Data</th>
                            <th>Destaque</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($interviews as $interview)
                        <tr>
                            <td>{{ Str::limit($interview->title, 40) }}</td>
                            <td>{{ $interview->interviewee ?? 'Não informado' }}</td>
                            <td>{{ $interview->interview_date ? $interview->interview_date->format('d/m/Y') : 'Não definida' }}</td>
                            <td>
                                <form action="{{ route('interviews.toggle-featured', $interview->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-sm {{ $interview->featured ? 'btn-warning' : 'btn-outline-secondary' }}">
                                        <i class="bi {{ $interview->featured ? 'bi-star-fill' : 'bi-star' }}"></i> 
                                        {{ $interview->featured ? 'Em destaque' : 'Destaque' }}
                                    </button>
                                </form>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary edit-interview-btn" 
                                            data-interview-id="{{ $interview->id }}"
                                            data-interview-title="{{ $interview->title }}"
                                            data-interview-description="{{ $interview->description }}"
                                            data-interview-youtube-id="{{ $interview->youtube_video_id }}"
                                            data-interview-date="{{ $interview->interview_date ? $interview->interview_date->format('Y-m-d\TH:i') : '' }}"
                                            data-interview-interviewee="{{ $interview->interviewee }}"
                                            data-interview-featured="{{ $interview->featured ? '1' : '0' }}">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <a href="https://www.youtube.com/watch?v={{ $interview->youtube_video_id }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="bi bi-youtube"></i>
                                    </a>
                                    <form action="{{ route('interviews.destroy', $interview->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Tem certeza que deseja excluir esta entrevista?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="alert alert-info mb-0">
                <i class="bi bi-info-circle"></i> Nenhuma entrevista cadastrada. Clique em "Nova Entrevista" para adicionar.
            </div>
        @endif
    </div>
</div>

<!-- Modal para Adicionar Entrevista -->
<div class="modal fade" id="addInterviewModal" tabindex="-1" aria-labelledby="addInterviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('interviews.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="addInterviewModalLabel">Nova Entrevista</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Título da Entrevista</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="interviewee" class="form-label">Nome do Entrevistado</label>
                        <input type="text" class="form-control" id="interviewee" name="interviewee">
                    </div>
                    <div class="mb-3">
                        <label for="youtube_video_id" class="form-label">ID do Vídeo no YouTube</label>
                        <input type="text" class="form-control" id="youtube_video_id" name="youtube_video_id" required
                               placeholder="Ex: dQw4w9WgXcQ (apenas o ID, não o link completo)">
                        <div class="form-text">Insira apenas o código do vídeo, não o link completo.</div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Descrição</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="interview_date" class="form-label">Data da Entrevista</label>
                        <input type="datetime-local" class="form-control" id="interview_date" name="interview_date">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="featured" name="featured">
                        <label class="form-check-label" for="featured">Destacar na Home</label>
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

<!-- Modal para Editar Entrevista -->
<div class="modal fade" id="editInterviewModal" tabindex="-1" aria-labelledby="editInterviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editInterviewForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editInterviewModalLabel">Editar Entrevista</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_title" class="form-label">Título da Entrevista</label>
                        <input type="text" class="form-control" id="edit_title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_interviewee" class="form-label">Nome do Entrevistado</label>
                        <input type="text" class="form-control" id="edit_interviewee" name="interviewee">
                    </div>
                    <div class="mb-3">
                        <label for="edit_youtube_video_id" class="form-label">ID do Vídeo no YouTube</label>
                        <input type="text" class="form-control" id="edit_youtube_video_id" name="youtube_video_id" required>
                        <div class="form-text">Insira apenas o código do vídeo, não o link completo.</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Descrição</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="edit_interview_date" class="form-label">Data da Entrevista</label>
                        <input type="datetime-local" class="form-control" id="edit_interview_date" name="interview_date">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="edit_featured" name="featured">
                        <label class="form-check-label" for="edit_featured">Destacar na Home</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Script para abrir o modal de edição com os dados corretos
        const editButtons = document.querySelectorAll('.edit-interview-btn');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.interviewId;
                const title = this.dataset.interviewTitle;
                const description = this.dataset.interviewDescription;
                const youtubeId = this.dataset.interviewYoutubeId;
                const date = this.dataset.interviewDate;
                const interviewee = this.dataset.interviewInterviewee;
                const featured = this.dataset.interviewFeatured === '1';
                
                document.getElementById('edit_title').value = title;
                document.getElementById('edit_description').value = description;
                document.getElementById('edit_youtube_video_id').value = youtubeId;
                document.getElementById('edit_interview_date').value = date;
                document.getElementById('edit_interviewee').value = interviewee;
                document.getElementById('edit_featured').checked = featured;
                
                document.getElementById('editInterviewForm').action = `/admin/interviews/${id}`;
                
                const editModal = new bootstrap.Modal(document.getElementById('editInterviewModal'));
                editModal.show();
            });
        });
    });
</script>
@endpush
@endif