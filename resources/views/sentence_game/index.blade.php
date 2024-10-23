<x-app-layout>
    @section('title', 'Sentence-Making Game')

    <div class="container">
        <h1 class="main-text mb-2 mt-3">Wybierz sekcję</h1>

        @foreach ($sections as $section)
            <div class="section-card">
                <h3 class="main-text mt-3">{{ $section->title }}</h3>
                <a href="{{ route('sentence_game.show', $section->id) }}" class="btn btn-primary">Przejdź do układanki</a>
            </div>
        @endforeach
    </div>
</x-app-layout>
