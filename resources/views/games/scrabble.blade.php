<x-app-layout>
    @section('title', 'Scrabble')

    <div class="container scrabble-game">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('games.index') }}" class="btn btn-secondary return-btn">Powrót do wyboru gier</a>
            </div>
            <div class="score-container">
                <span>Wynik: </span><span id="score">0</span>
            </div>
        </div>

        <div id="word-guess-area">
            <p id="hint">Zgadnij słowa z podanych liter!</p>
            <div id="completion-message" class="alert alert-success mt-4" style="display: none;"></div>
            <div id="available-letters" class="mb-4"></div>
            <input type="text" id="guess-input" class="form-control mb-2" placeholder="Enter your guess">

            <div class="d-flex justify-content-start align-items-center">
                <button id="submit-guess" class="btn btn-success me-2">Sprawdź słowo</button>
                <button id="generateNewBatch" class="btn btn-primary me-2">Wygeneruj na nowo</button>
                <button id="hint-btn" class="btn btn-warning me-2">Podpowiedź</button>
                <button id="giveUp" class="btn btn-danger">Poddaj się</button>
            </div>
        </div>

        <div id="game-result" class="alert alert-info mt-3" style="display: none;"></div>

        <div class="row mt-5">
            <div class="col-md-6">
                <h4>Trafione słowa</h4>
                <ul id="correct-guesses" class="list-group"></ul>
            </div>
            <div class="col-md-6">
                <h4>Chybione słowa</h4>
                <ul id="invalid-guesses" class="list-group"></ul>
            </div>
        </div>

        <div id="hidden-words" style="display: none;">
            @foreach ($words as $word)
                <span class="hidden-word" data-word="{{ $word->english_word }}" data-polish="{{ $word->polish_word }}">{{ $word->polish_word }}</span>
            @endforeach
        </div>


    </div>

    @vite('resources/js/scrabble-game.js')
    @vite('resources/css/scrabble-game.css')
</x-app-layout>
