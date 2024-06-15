<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Testy słownikowo - gramatyczne dla: ') }} {{ $test->title }}
        </h2>
    </x-slot>

    <div class="container mt-5">
        <ul class="nav nav-tabs" id="testTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="missing-letters-tab" data-bs-toggle="tab" href="#missing-letters" role="tab" aria-controls="missing-letters" aria-selected="true">Missing Letters Test</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="translation-test-tab" data-bs-toggle="tab" href="#translation-test" role="tab" aria-controls="translation-test" aria-selected="false">Translation Test</a>
            </li>
        </ul>
        <div class="tab-content mt-4" id="testTabContent">
            <div class="tab-pane fade show active" id="missing-letters" role="tabpanel" aria-labelledby="missing-letters-tab">
                <h3>Wypełnij brakujące litery</h3>
                <form id="missingLettersForm">
                    @php
                        $selectedWords = $words->random(5);
                    @endphp
                    @foreach($selectedWords as $word)
                        @php
                            $vowels = ['a', 'e', 'i', 'o', 'u'];
                            $wordStr = $word->english_word;
                            $letters = str_split($wordStr);
                            $indices = array_keys(array_intersect($letters, $vowels));
                            $randomIndex = $indices[array_rand($indices)];
                            $correctLetter = $letters[$randomIndex];
                            $letters[$randomIndex] = '<span class="missing-letter"><input type="text" class="form-control d-inline-block w-auto" name="word[' . $word->id . ']" maxlength="1" required></span>';
                            $wordWithBlank = implode('', $letters);
                        @endphp
                        <div class="mb-3">
                            <label class="form-label">Wpisz brakującą spółgłoskę: {!! $wordWithBlank !!} ({{ $word->polish_word }}) </label>
                            <input type="hidden" name="correct_letter[{{ $word->id }}]" value="{{ $correctLetter }}">
                        </div>
                    @endforeach
                    <button type="button" class="btn btn-primary">Check</button>
                </form>
            </div>
            <div class="tab-pane fade" id="translation-test" role="tabpanel" aria-labelledby="translation-test-tab">
                <h3>Translation Test</h3>
                <form id="translationForm">
                    @foreach($words as $word)
                        <div class="mb-3">
                            <label for="translation-{{ $word->id }}" class="form-label">Translate to English: {{ $word->polish_word }}</label>
                            <input type="text" class="form-control" id="translation-{{ $word->id }}" name="translation[{{ $word->id }}]" required>
                            <input type="hidden" name="correct_translation[{{ $word->id }}]" value="{{ $word->english_word }}">
                        </div>
                    @endforeach
                    <button type="button" class="btn btn-primary">Check</button>
                </form>
            </div>
        </div>
    </div>


    @vite('resources/js/test.js')
</x-app-layout>
