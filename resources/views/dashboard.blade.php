<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="jumbotron">
        <h1 class="display-4">Witaj w Nauce Angielskiego</h1>
        <p class="lead">Rozwijaj swoje umiejętności języka angielskiego dzięki naszej szerokiej gamie kursów i zasobów.
        </p>
        <hr class="my-4">
        <p>To jest stronaa główna. pwitanie i objaśnienie zakłądek</p>
    </div>

    <div class="container mt-5">
        <h2>Szukaj Słów</h2>
        <form method="GET" action="{{ route('words.search') }}" id="wordSearchForm">
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="searchWord"
                    placeholder="Wpisz słowo (polskie lub angielskie)" aria-label="Szukaj słowa" autocomplete="off">
            </div>
            <ul id="wordSuggestions" class="list-group" style="position: absolute; z-index: 1000;"></ul>
        </form>
    </div>

@vite('resources/js/dashboard.js')
</x-app-layout>
