<x-app-layout>
    @section('title', 'Scrabble')

    <div class="modal fade" id="configModal" tabindex="-1" aria-labelledby="configModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="configModalLabel">Konfiguracja gry</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="configForm">
                        <div class="mb-3">
                            <label for="wordCount" class="form-label">Wybierz liczbę wyrazów (2-7)</label>
                            <input type="number" class="form-control" id="wordCount" min="2" max="7"
                                value="3" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Wybierz co najmniej jeden typ słów:</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="nounCheckbox" value="Noun (Rzeczownik)">
                                <label class="form-check-label" for="nounCheckbox">Noun (Rzeczownik)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="verbCheckbox" value="Verb (Czasownik)">
                                <label class="form-check-label" for="verbCheckbox">Verb (Czasownik)</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="adjCheckbox" value="Adjective (Przymiotnik)">
                                <label class="form-check-label" for="adjCheckbox">Adjective (Przymiotnik)</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Wskażnik pokrycia z rozsypanymi słowami</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="feedback" id="feedbackTrue"
                                    value="true" checked>
                                <label class="form-check-label" for="feedbackTrue">Włącz</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="feedback" id="feedbackFalse"
                                    value="false">
                                <label class="form-check-label" for="feedbackFalse">Wyłącz</label>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="startGameBtn">Rozpocznij grę</button>
                </div>
            </div>
        </div>
    </div>


    <div id="gameContent" class="container mt-3 scrabble-game" style="display: none;">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <a href="{{ route('games.index') }}" class="btn btn-secondary return-btn">Powrót do wyboru gier</a>
            </div>
        </div>

        <div id="word-guess-area">
            <p id="hint">Zgadnij słowa z podanych liter!</p>
            <div id="completion-message" class="alert alert-success mt-4" style="display: none;"></div>
            <div id="available-letters" class="mb-4"></div>

            <div class="input-group mb-2">
                <input type="text" id="guess-input" class="form-control" placeholder="Enter your guess">
                <span id="guess-feedback" class="input-group-text">❌</span>
            </div>

            <div class="d-flex justify-content-start align-items-center">
                <button id="submit-guess" class="btn btn-success me-2">Sprawdź słowo</button>
                <button id="generateNewBatch" class="btn btn-primary me-2">Wygeneruj na nowo</button>
                <button id="hint-btn" class="btn btn-warning me-2">Podpowiedź</button>
                <button id="giveUp" class="btn btn-danger">Poddaj się</button>
            </div>
        </div>

        <div id="game-result" class="alert alert-info mt-3" style="display: none;"></div>

        <div class="row mt-5">
            <div class="col-md-6">
                <h4>Trafione słowa</h4>
                <ul id="correct-guesses" class="list-group"></ul>
            </div>
            <div class="col-md-6">
                <h4>Chybione słowa</h4>
                <ul id="invalid-guesses" class="list-group"></ul>
            </div>
        </div>

        <div id="hidden-words" style="display: none;"></div>
    </div>


    @vite('resources/js/scrabble-game.js')
    @vite('resources/css/scrabble-game.css')
</x-app-layout>
