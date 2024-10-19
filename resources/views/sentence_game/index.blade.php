<x-app-layout>
    @section('title', 'Sentence-Making Game')

    <div class="container">
        <h1>Wybierz sekcję</h1>

        @foreach ($sections as $section)
            <div class="section-card">
                <h3>{{ $section->title }}</h3>
                <a href="{{ route('sentence_game.show', $section->id) }}" class="btn btn-primary">Przejdź do układanki</a>
            </div>
        @endforeach
    </div>
</x-app-layout>
