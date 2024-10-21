<x-app-layout>
    @section('title', 'Kursy ze słownictwem')

    <div class="container mt-5">
        <div class="row">
            @if ($courses->isEmpty())
                <div class="col-md-12 text-center">
                    <h2 class="text-muted">UPS... Ta podstrona jest w trakcie prac. Przepraszamy</h2>
                </div>
            @else
                @foreach ($courses as $course)
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>{{ $course->title }}</span>
                                @if (Auth::check() && Auth::user()->usertype === 'Admin')
                                    <div>
                                        <a href="{{ route('course.edit', $course) }}"
                                            class="btn btn-sm btn-outline-secondary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z" />
                                            </svg>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="confirmDelete('{{ route('course.destroy', $course) }}')">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                                <path
                                                    d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{ $course->description }}</p>
                                <a href="{{ route('course-words.show', $course->id) }}" class="btn btn-primary">Przejdź
                                    do kursu</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

            @if (Auth::check() && Auth::user()->usertype === 'Admin')
                <div class="col-md-6">
                    <div class="card mb-4">
                        <div class="card-header">
                            Dodaj Nowy Kurs
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title">Stwórz nowy kurs</h5>
                            <p class="card-text">Dodaj nowy kurs do swojej oferty.</p>
                            <a href="{{ route('course.create') }}" class="btn btn-primary">Dodaj nowy kurs</a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Potwierdź usunięcie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Czy na pewno chcesz usunąć to słowo?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Usuń</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let deleteUrl = '';

        function confirmDelete(url) {
            deleteUrl = url;
            var myModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            myModal.show();
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = deleteUrl;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        });
    </script>
</x-app-layout>
