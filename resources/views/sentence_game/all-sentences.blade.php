<x-app-layout>
    @section('title', 'All Sentences for "{{ $section->title }}"')

    <div class="container">
        <h1 class="mt-2 mb-2">Wszystkie zdania dla: "{{ $section->title }}"</h1>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Zdanie</th>
                    <th>Edytuj</th>
                    <th>Usuń</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sentences as $sentence)
                    <tr id="sentence-row-{{ $sentence->id }}">
                        <td>
                            <span id="sentence-text-{{ $sentence->id }}">{{ $sentence->sentence }}</span>
                            <input type="text" id="edit-input-{{ $sentence->id }}" class="form-control d-none"
                                value="{{ $sentence->sentence }}">
                        </td>
                        <td>
                            <button class="btn btn-secondary btn-edit" data-id="{{ $sentence->id }}">Edytuj</button>
                            <button class="btn btn-success btn-save d-none"
                                data-id="{{ $sentence->id }}">Zapisz</button>
                            <button class="btn btn-secondary btn-cancel d-none"
                                data-id="{{ $sentence->id }}">Anuluj</button>
                        </td>
                        <td>
                            <button class="btn btn-danger btn-delete" data-id="{{ $sentence->id }}">Usuń</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Potwierdzenie usunięcia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Czy na pewno chcesz usunąć to zdanie?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Usuń</button>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    document.getElementById(`sentence-text-${id}`).classList.add('d-none');
                    document.getElementById(`edit-input-${id}`).classList.remove('d-none');
                    this.classList.add('d-none');
                    document.querySelector(`.btn-save[data-id="${id}"]`).classList.remove('d-none');
                    document.querySelector(`.btn-cancel[data-id="${id}"]`).classList.remove(
                        'd-none');
                });
            });

            document.querySelectorAll('.btn-save').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const newSentence = document.getElementById(`edit-input-${id}`).value;

                    fetch(`/sentence_game/${id}/update`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({
                                sentence: newSentence
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById(`sentence-text-${id}`).innerText =
                                    newSentence;
                                document.getElementById(`sentence-text-${id}`).classList.remove(
                                    'd-none');
                                document.getElementById(`edit-input-${id}`).classList.add(
                                    'd-none');
                                document.querySelector(`.btn-edit[data-id="${id}"]`).classList
                                    .remove('d-none');
                                document.querySelector(`.btn-save[data-id="${id}"]`).classList
                                    .add('d-none');
                                document.querySelector(`.btn-cancel[data-id="${id}"]`).classList
                                    .add('d-none');
                            } else {
                                alert('Error updating sentence');
                            }
                        });
                });
            });

            document.querySelectorAll('.btn-cancel').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.id;
                    document.getElementById(`sentence-text-${id}`).classList.remove('d-none');
                    document.getElementById(`edit-input-${id}`).classList.add('d-none');
                    document.querySelector(`.btn-edit[data-id="${id}"]`).classList.remove('d-none');
                    document.querySelector(`.btn-save[data-id="${id}"]`).classList.add('d-none');
                    document.querySelector(`.btn-cancel[data-id="${id}"]`).classList.add('d-none');
                });
            });

            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function() {
                    deleteSentenceId = this.dataset.id;
                    const modal = new bootstrap.Modal(document.getElementById(
                        'deleteConfirmationModal'));
                    modal.show();
                });
            });

            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                if (deleteSentenceId) {
                    fetch(`/sentence_game/${deleteSentenceId}/delete`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.getElementById(`sentence-row-${deleteSentenceId}`).remove();
                                const modal = bootstrap.Modal.getInstance(document.getElementById(
                                    'deleteConfirmationModal'));
                                modal.hide();
                            } else {
                                alert('Error deleting sentence');
                            }
                        });
                }
            });
        });
    </script>

    @vite('resources/css/sentence-play.css')
</x-app-layout>
