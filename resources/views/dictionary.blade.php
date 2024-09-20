<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Słownik') }}
        </h2>
    </x-slot>

    <div class="container mt-5">
        <h3>Wszystkie słowa z ich tłumaczeniami</h3>


        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <form action="{{ route('dictionary') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="english_word" class="form-label">Słowo Angielskie:</label>
                    <input type="text" class="form-control" id="english_word" name="english_word" required>
                </div>
                <div class="mb-3">
                    <label for="pronunciation" class="form-label">Angielska Wymowa</label>
                    <input type="text" class="form-control" id="pronunciation" name="pronunciation">
                </div>
                <div class="mb-3">
                    <label for="word_type" class="form-label">Typ słowa</label>
                    <select class="form-control" id="word_type" name="word_type" required>
                        <option value="Noun (Rzeczownik)">Rzeczownik</option>
                        <option value="Verb (Czasownik)">Czasownik</option>
                        <option value="Adjective (Przymiotnik)">Przymiotnik</option>
                        <option value="Adverb (Przysłowek)">Przysłowek</option>
                        <option value="Pronoun (Zaimek)">Zaimek</option>
                        <option value="Preposition (Przyimek)">Przyimek</option>
                        <option value="Conjunction (Spójnik)">Spójnik</option>
                        <option value="Interjection (Wykrzyknik)">Wykrzyknik</option>
                        <option value="Idiom (Idiom)">Idiom</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="polish_word" class="form-label">Polskie tłumaczenie:</label>
                    <input type="text" class="form-control" id="polish_word" name="polish_word" required>
                </div>
                <button type="submit" class="btn btn-primary">Dodaj wpis</button>
            </form>
        </div>

        <h2>Szukaj Słów</h2>
        <form method="GET" action="{{ route('words.search') }}" id="wordSearchForm">
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="searchWord" placeholder="Wpisz słowo (polskie lub angielskie)" aria-label="Szukaj słowa" autocomplete="off">
            </div>
            <ul id="wordSuggestions" class="list-group" style="position: absolute; z-index: 1000;"></ul>
        </form>

        <div style="overflow-x: auto; overflow-y: auto; max-height: 400px; max-width: 100%;">
        <table class="table mt-3 table-bordered">
            <thead>
                <tr>
                    <th>Angielskie słowo:</th>
                    <th>Angielska wymowa:</th>
                    <th>Typ słowa:</th>
                    <th>Polskie tłumaczenie</th>
                    <th>Akcje</th>
                </tr>
            </thead>
            <tbody id="wordTableBody">
                @foreach ($words as $word)
                    <tr id="word-row-{{ $word->id }}">
                        <td class="english-word">{{ $word->english_word }}</td>
                        <td class="pronunciation">{{ $word->pronunciation }}</td>
                        <td class="word-type">{{ ucfirst($word->word_type) }}</td>
                        <td class="polish-word">{{ $word->polish_word }}</td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary edit-btn" data-word-id="{{ $word->id }}">Edytuj</button>
                            <button class="btn btn-sm btn-outline-success save-btn" data-word-id="{{ $word->id }}" style="display: none;">Zapisz</button>

                            <form action="{{ route('words.destroy', $word->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Czy na pewno chcesz usunąć to słowo?')">Usuń</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>


    @vite('resources/js/dictionary.js')
</x-app-layout>
