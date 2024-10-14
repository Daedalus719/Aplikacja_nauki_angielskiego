<x-app-layout>
    @section('title', 'Testy słownikowo - gramatyczne dla')

    <div class="container mt-5">
        <div class="row" id="testCards">
            <div class="col-md-6">
                <div class="card test-card text-center p-4 bg-primary text-white" data-test="missing-letters">
                    <h3>Missing Letters Test</h3>
                    <p>Wypełnij brakujące spółgłoskę</p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card test-card text-center p-4 bg-secondary text-white" data-test="translation-test">
                    <h3>Translation Test</h3>
                    <p>Przetłumacz polskie słowa na angielski</p>
                </div>
            </div>
        </div>

        <div id="missingLettersContent" class="test-content" style="display:none;">
            <h3>Test Gramatyczny</h3>
            <p>Wpisz brakującą spółgłoskę w poniższych wyrażeniach:</p>
            <form id="missingLettersForm" class="mt-2">
                <!-- inserted by js-->
            </form>
            <div id="resultMessage" class="mt-3" style="display:none;"></div>
            <button type="button" class="btn btn-success mt-3" id="checkMissingLetters">Sprawdź</button>
            <button type="button" class="btn btn-primary mt-3" id="tryAgainMissingLetters">Spróbuj ponownie</button>
            <button type="button" class="btn btn-secondary mt-3 back-btn">Wróć do wyboru testów</button>
        </div>

        <div id="translationTestContent" class="test-content" style="display:none;">
            <h3>Test Słownikowy</h3>
            <p>Wpisz odpowiednie tłumaczenia dla poniższych słów:</p>
            <form id="translationForm" class="mt-2">
                <!-- inserted by js-->
            </form>
            <div id="translationResultMessage" style="display:none;"></div>
            <button type="button" class="btn btn-success mt-3" id="checkTranslation">Sprawdź</button>
            <button type="button" class="btn btn-primary mt-3" id="tryAgainTranslation">Spróbuj ponownie</button>
            <button type="button" class="btn btn-secondary mt-3 back-btn">Wróć do wyboru testów</button>
        </div>
    </div>

    <script>
        window.wordsData = @json($words);
    </script>

    @vite('resources/js/test.js')
</x-app-layout>
