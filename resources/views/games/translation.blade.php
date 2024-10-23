<x-app-layout>
    @section('title', 'Znajdź poprawne słowo')

    <link rel="stylesheet" href="{{ asset('css/translation-game.css') }}">

    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('games.index') }}" class="btn btn-secondary mt-3 return-btn">Powrót do wyboru gier</a>

            <div class="score-container">
                <span class="main-text">Wynik: </span><span class="main-text" id="score">0</span>
            </div>
        </div>

        <p class="text-center main-text">Wybierz poprawne tłumaczenie dla słowa poniżej:</p>

        <div id="game-container" class="mt-5 text-center">
            <h3 class="main-text" id="english-word"></h3>
            <div class="mt-4">
                <button class="btn btn-lg btn-primary option-btn" data-answer="true"></button>
                <button class="btn btn-lg btn-primary option-btn" data-answer="false"></button>
            </div>
        </div>

        <div id="result-message" class="mt-3 text-center"></div>
    </div>

    @vite('resources/js/translation-game.js')
</x-app-layout>
