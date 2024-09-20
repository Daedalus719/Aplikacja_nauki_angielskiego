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
                        <option value="noun">Rzeczownik</option>
                        <option value="verb">Czasownik</option>
                        <option value="adjective">Przymiotnik</option>
                        <option value="adverb">Przysłowek</option>
                        <option value="pronoun">Zaimek</option>
                        <option value="preposition">Przyimek</option>
                        <option value="conjunction">Spójnik</option>
                        <option value="interjection">Wykrzyknik</option>
                        <option value="idiom">Idiom</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="polish_word" class="form-label">Polskie tłumaczenie:</label>
                    <input type="text" class="form-control" id="polish_word" name="polish_word" required>
                </div>
                <button type="submit" class="btn btn-primary">Dodaj wpis</button>
            </form>
        </div>


        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Angielskie słowo:</th>
                    <th>Angielska wymowa:</th>
                    <th>Typ słowa:</th>
                    <th>Polskie tłumaczenie</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($words as $word)
                    <tr>
                        <td>{{ $word->english_word }}</td>
                        <td>{{ $word->pronunciation }}</td>
                        <td>{{ ucfirst($word->word_type) }}</td>
                        <td>{{ $word->polish_word }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
