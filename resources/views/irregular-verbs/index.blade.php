<x-app-layout>
    @section('title', 'Czasowniki Nieregularne')

    <div class="container mt-5">

        @if (Auth::check() && Auth::user()->usertype === 'Admin')
            <h3 class="mb-2">Dodaj nowy wpis</h3>
            <form id="addVerbForm" action="{{ route('irregular-verbs.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="verb_1st_form" class="form-label">I forma bezokolicznik (infinitive)</label>
                    <input type="text" class="form-control" id="verb_1st_form" name="verb_1st_form" required>
                </div>
                <div class="mb-3">
                    <label for="verb_2nd_form" class="form-label">II forma (past tense)</label>
                    <input type="text" class="form-control" id="verb_2nd_form" name="verb_2nd_form" required>
                </div>
                <div class="mb-3">
                    <label for="verb_3rd_form" class="form-label">III forma (past participle)</label>
                    <input type="text" class="form-control" id="verb_3rd_form" name="verb_3rd_form" required>
                </div>
                <div class="mb-3">
                    <label for="polish_translation" class="form-label">Tłumaczenie</label>
                    <input type="text" class="form-control" id="polish_translation" name="polish_translation"
                        required>
                </div>
                <button type="submit" class="btn btn-success">Dodaj Wpis</button>
            </form>
        @endif

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th style="display: none">ID</th>
                    <th>TTS</th>
                    <th>I forma bezokolicznik (infinitive)</th>
                    <th>II forma (past tense)</th>
                    <th>III forma (past participle)</th>
                    <th>Tłumaczenie</th>
                    @if (Auth::check() && Auth::user()->usertype === 'Admin')
                        <th>Akcje</th>
                    @endif
                </tr>
            </thead>
            <tbody id="verbsTableBody">
                @foreach ($irregularVerbs as $verb)
                    <tr>
                        <td class="verb-id" style="display:none;">{{ $verb->id }}</td>
                        <td><button class="btn btn-sm btn-outline-primary tts-btn"
                                data-verb-id="{{ $verb->id }}">🔊</button></td>
                        <td class="verb-1st">{{ $verb->verb_1st_form }}</td>
                        <td class="verb-2nd">{{ $verb->verb_2nd_form }}</td>
                        <td class="verb-3rd">{{ $verb->verb_3rd_form }}</td>
                        <td class="polish-translation">{{ $verb->polish_translation }}</td>
                        @if (Auth::check() && Auth::user()->usertype === 'Admin')
                            <td>
                                <button class="btn btn-sm btn-outline-secondary edit-btn"
                                    data-word-id="{{ $verb->id }}">Edytuj</button>
                                <button class="btn btn-sm btn-outline-success save-btn"
                                    data-word-id="{{ $verb->id }}" style="display: none;">Zapisz</button>
                                <form action="{{ route('irregular-verbs.destroy', $verb->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Usuń</button>
                                </form>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1"
            aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmationModalLabel">Potwierdzenie usunięcia</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Czy na pewno chcesz usunąć ten czasownik?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                        <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Usuń</button>
                    </div>
                </div>
            </div>
        </div>


    </div>

    @vite('resources/js/irregular-verbs.js')
</x-app-layout>
