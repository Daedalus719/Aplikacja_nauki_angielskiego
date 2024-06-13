<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="container">
        <h1>Test z brakującymi samogłoskami: {{ $course->title }}</h1>
        <form method="POST" action="#">
            @csrf
            @foreach($words as $word)
                <div class="mb-3">
                    <label for="word{{ $loop->index }}" class="form-label">{{ preg_replace('/[aeiou]/i', '_', $word->english_word) }}</label>
                    <input type="text" class="form-control" id="word{{ $loop->index }}" name="word{{ $loop->index }}" required>
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary">Sprawdź</button>
        </form>
    </div>
</x-app-layout>
