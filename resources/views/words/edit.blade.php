<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="container">
        <h2>Edytuj słowo</h2>
        <form method="POST" action="{{ route('words.update', [$course, $word]) }}">
            @csrf
            @method('PATCH')
            <div class="mb-3">
                <label for="english_word" class="form-label">Angielskie Słowo</label>
                <input type="text" class="form-control" id="english_word" name="english_word" value="{{ $word->english_word }}" required>
            </div>
            <div class="mb-3">
                <label for="polish_word" class="form-label">Polskie Słowo</label>
                <input type="text" class="form-control" id="polish_word" name="polish_word" value="{{ $word->polish_word }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Zaktualizuj Słowo</button>
        </form>
    </div>
</x-app-layout>
