<x-app-layout>
    @section('title', 'Fraszki')

    <div class="container mt-5">
        <h2 class="text-center">Kliknij na kartę aby zobaczyć tłumaczenie tego słowa</h2>

        <a href="{{ route('games.index') }}" class="btn btn-secondary return-btn">Powrót do wyboru gier</a>

        <div class="card-container text-center">
            <div id="card" class="card">
                <div class="card-side front">
                    <h3 id="polish-word">{{ $words[0]->polish_word }}</h3>
                </div>
                <div class="card-side back">
                    <h3 id="english-word">{{ $words[0]->english_word }}</h3>
                </div>
            </div>
        </div>

        <div class="navigation">
            <button id="prev" class="btn btn-outline-primary" disabled>&#8592; Poprzednie</button>
            <button id="next" class="btn btn-outline-primary">Następne &#8594;</button>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('games.flashcards-test') }}" class="btn btn-primary btn-lg">Sprawdź się</a>
        </div>
    </div>

    <script>
        window.wordsData = @json($words);
    </script>

    @vite('resources/css/flashcards.css')
    @vite('resources/js/flashcards.js')
</x-app-layout>
