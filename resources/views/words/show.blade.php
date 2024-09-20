<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Szczegóły słowa') }}: {{ $word->english_word }}
        </h2>
    </x-slot>

    <div class="container mt-5">
        <h2>{{ $word->english_word }} - {{ $word->polish_word }}</h2>
        <p><strong>Wymowa:</strong> {{ $word->pronunciation }}</p>
        <p><strong>Typ słowa:</strong> {{ $word->word_type }}</p>
        <p><strong>Polski:</strong> {{ $word->polish_word }}</p>
        <a href="{{ route('dictionary') }}" class="btn btn-primary">Powrót do słownika</a>
    </div>
</x-app-layout>
