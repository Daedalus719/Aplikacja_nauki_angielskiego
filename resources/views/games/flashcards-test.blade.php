<x-app-layout>
    @section('title', 'Fraszki - Test')

    <div class="container mt-5">
        <h2 class="text-center main-text">Sprawdź swoją wiedzę</h2>

        <div class="test-container text-center">
            <div id="test-card">
                <h3 id="polish-word">{{ $words[0]->polish_word }}</h3>
                <input type="text" id="answer" placeholder="Wpisz angielskie słowo" class="form-control mt-3"
                    data-index="0">
                <div id="result-message" class="mt-3 "></div>
            </div>
        </div>

        <div class="navigation mt-4">
            <button id="prev" class="btn btn-outline-primary" disabled>&#8592; Poprzednie</button>
            <button id="next" class="btn btn-outline-primary">Następne &#8594;</button>
        </div>

        <div class="text-center mt-4">
            <button id="submit" class="btn btn-secondary" disabled>Sprawdź odpowiedzi</button>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('games.flashcards') }}" class="btn btn-secondary">Powrót do fraszek</a>
        </div>
    </div>

    <script>
        window.wordsData = @json($words);
    </script>

    @vite('resources/css/flashcards.css')
    @vite('resources/js/flashcards-test.js')
</x-app-layout>
