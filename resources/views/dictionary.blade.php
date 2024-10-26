<x-app-layout>
    @section('title', 'Słownik')

    <div class="container-fluid d-flex flex-column" style="height: 100vh;">

        @if (Auth::check() && (Auth::user()->usertype === 'Admin' || Auth::user()->usertype === 'Moderator'))
            <div class="mb-3 mt-3">
                <form id="addWordForm" action="{{ route('dictionary') }}" method="POST">
                    @csrf
                    <div class="mb-3 position-relative">
                        <label for="english_word" class="form-label label-color">Słowo Angielskie:</label>
                        <input type="text" class="form-element" id="english_word" name="english_word" required
                            autocomplete="off">
                        <ul id="englishWordSuggestions" class="list-group position-absolute" style="z-index: 1000;">
                        </ul>
                    </div>

                    <div class="mb-3">
                        <label for="word_type" class="form-label label-color">Typ słowa:</label>
                        <select class="form-element" id="word_type" name="word_type" required>
                            <option class="element-background gray-border label-color" value="Noun (Rzeczownik)">Rzeczownik</option>
                            <option class="element-background gray-border label-color" value="Verb (Czasownik)">Czasownik</option>
                            <option class="element-background gray-border label-color" value="Adjective (Przymiotnik)">Przymiotnik</option>
                            <option class="element-background gray-border label-color" value="Adverb (Przysłówek)">Przysłówek</option>
                            <option class="element-background gray-border label-color" value="Pronoun (Zaimek)">Zaimek</option>
                            <option class="element-background gray-border label-color" value="Proverb (Przysłowie)">Przysłowie</option>
                            <option class="element-background gray-border label-color" value="Preposition (Przyimek)">Przyimek</option>
                            <option class="element-background gray-border label-color" value="Conjunction (Spójnik)">Spójnik</option>
                            <option class="element-background gray-border label-color" value="Interjection (Wykrzyknik)">Wykrzyknik</option>
                            <option class="element-background gray-border label-color" value="Idiom (Idiom)">Idiom</option>
                        </select>
                    </div>




                    <div class="mb-3">
                        <label for="polish_word" class="form-label label-color">Polskie tłumaczenie:</label>
                        <input type="text" class="form-element" id="polish_word" name="polish_word" required>
                    </div>

                    <button type="submit" class="btn btn-success">Dodaj wpis</button>
                </form>
            </div>
        @endif

        <div class="flex-grow-1" style="overflow-x: auto; overflow-y: auto;">
            <table class="table mt-3 table-bordered" id="wordTable">
                <thead>
                    <tr>
                        <th>Akcje</th>
                        <th>Angielskie słowo:</th>
                        <th>Typ słowa:</th>
                        <th>Polskie tłumaczenie</th>
                    </tr>
                </thead>
                <tbody id="wordTableBody">
                    <!-- Rows will be dynamically added here by JS -->
                </tbody>
            </table>
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

    </div>

    @vite('resources/css/app.css')
    @vite('resources/js/dictionary.js')
</x-app-layout>
