<x-app-layout>
    <div class="container">
        <h1>Sekcje</h1>

        @if (Auth::check() && (Auth::user()->usertype === 'Admin' || Auth::user()->usertype === 'Moderator'))
            <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#createModal">Stwórz nową
                sekcję</button>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($sections->count())
            <ul class="list-group">
                @foreach ($sections as $section)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>{{ $section->title }}</strong>
                            @if (Auth::check() && (Auth::user()->usertype === 'Admin' || Auth::user()->usertype === 'Moderator'))
                                <div>
                                    <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                        data-bs-target="#editModal" data-title="{{ $section->title }}"
                                        data-id="{{ $section->id }}">Edytuj</button>
                                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteConfirmationModal"
                                        data-id="{{ $section->id }}">Usuń</button>
                                </div>
                            @endif
                        </div>
                        <div class="mt-2">
                            <a href="{{ route('sections.show', $section->id) }}" class="btn btn-sm btn-info">Przejrzyj
                                reguły</a>
                            <a href="{{ route('tasks.index', $section->id) }}" class="btn btn-sm btn-info">Przejdź do
                                zadań</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p>Brak dostępnych sekcji!</p>
        @endif
    </div>

    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Stwórz nową sekcję</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="createForm" method="POST" action="{{ route('sections.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="createTitle" class="form-label">Tytuł sekcji</label>
                            <input type="text" class="form-control" id="createTitle" name="title" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                        <button type="submit" class="btn btn-success">Stwórz sekcję</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edytuj sekcję</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editTitle" class="form-label">Tytuł sekcji</label>
                            <input type="text" class="form-control" id="editTitle" name="title" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                        <button type="submit" class="btn btn-success">Zapisz zmiany</button>
                    </div>
                </form>
            </div>
        </div>
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
                    Czy na pewno chcesz usunąć tę sekcję?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Usuń</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        const editButtons = document.querySelectorAll('[data-bs-target="#editModal"]');
        const deleteButtons = document.querySelectorAll(
            '[data-bs-toggle="modal"][data-bs-target="#deleteConfirmationModal"]');
        const deleteForm = document.getElementById('deleteForm');
        const editForm = document.getElementById('editForm');

        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const sectionId = this.getAttribute('data-id');
                const sectionTitle = this.getAttribute('data-title');
                document.getElementById('editTitle').value = sectionTitle;
                editForm.action = `/sections/${sectionId}`;
            });
        });

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const sectionId = this.getAttribute('data-id');
                deleteForm.action = `/sections/${sectionId}`;
            });
        });
    </script>


</x-app-layout>
