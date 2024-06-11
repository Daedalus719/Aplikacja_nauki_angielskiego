<!-- resources/views/courses/show.blade.php -->

<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="jumbotron">
        <h1 class="display-4">{{ $course->title }}</h1>
        <p class="lead">{{ $course->description }}</p>
        <hr class="my-4">
        <p>Zacznij naukę już teraz i śledź swój postęp w miarę nauki.</p>
    </div>

    <div class="container">
        <h2>Lista słów</h2>
        <ul class="list-group mb-4">
            @foreach($course->words as $word)
                <li class="list-group-item">{{ $word->english_word }} - {{ $word->polish_word }}</li>
            @endforeach
        </ul>

        <h2>Dodaj nowe słowo</h2>
        <form method="POST" action="{{ route('words.store', $course) }}">
            @csrf
            <div class="mb-3">
                <label for="english_word" class="form-label">Angielskie Słowo</label>
                <input type="text" class="form-control" id="english_word" name="english_word" required>
            </div>
            <div class="mb-3">
                <label for="polish_word" class="form-label">Polskie Słowo</label>
                <input type="text" class="form-control" id="polish_word" name="polish_word" required>
            </div>
            <button type="submit" class="btn btn-primary">Dodaj Słowo</button>
        </form>
    </div>
</x-app-layout>
