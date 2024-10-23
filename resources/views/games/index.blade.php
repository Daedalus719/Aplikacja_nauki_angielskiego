<x-app-layout>
    @section('title', 'Games')

    <div class="container mt-5">
        <h2 class="main-text">Wybierz grę</h2>
        <div class="mt-4">
            <a href="{{ route('games.translation') }}" class="btn btn-success btn-lg">Wskaż poprawne słowo</a>
            <a href="{{ route('games.scrabble') }}" class="btn btn-success btn-lg">Scrabble</a>
            <a href="{{ route('games.flashcards') }}" class="btn btn-success btn-lg">Fiszki</a>
        </div>
    </div>
</x-app-layout>
