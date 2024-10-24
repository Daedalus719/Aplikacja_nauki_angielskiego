document.addEventListener('DOMContentLoaded', function () {
    const configModal = new bootstrap.Modal(document.getElementById('configModal'));
    configModal.show();

    const startGameBtn = document.getElementById('startGameBtn');
    startGameBtn.addEventListener('click', function () {
        const wordCount = document.getElementById('wordCount').value;
        const wordTypes = [];
        if (document.getElementById('nounCheckbox').checked) wordTypes.push('Noun (Rzeczownik)');
        if (document.getElementById('verbCheckbox').checked) wordTypes.push('Verb (Czasownik)');
        if (document.getElementById('adjCheckbox').checked) wordTypes.push('Adjective (Przymiotnik)');
        const feedbackAssistant = document.querySelector('input[name="feedback"]:checked').value;

        if (wordTypes.length === 0) {
            alert('Please select at least one word type.');
            return;
        }

        configModal.hide();
        startGame(wordCount, wordTypes, feedbackAssistant);
    });

    function startGame(wordCount, wordTypes, feedbackAssistant) {
        document.getElementById('gameContent').style.display = 'block';

        const guessFeedback = document.getElementById('guess-feedback');
        guessFeedback.style.display = feedbackAssistant === 'true' ? 'block' : 'none';

        fetch(`/fetch-scrabble-words?wordCount=${wordCount}&wordTypes=${wordTypes.join(',')}`)
            .then(response => response.json())
            .then(data => {
                const words = data.words;

                const hiddenWordsDiv = document.getElementById('hidden-words');
                hiddenWordsDiv.innerHTML = '';

                words.forEach(word => {
                    const wordSpan = document.createElement('span');
                    wordSpan.classList.add('hidden-word');
                    wordSpan.dataset.word = word.english_word;
                    wordSpan.dataset.polish = word.polish_word;
                    hiddenWordsDiv.appendChild(wordSpan);
                });

                initializeScrabbleGame(words, feedbackAssistant);
            })
            .catch(error => console.error('Error fetching words:', error));
    }


    function initializeScrabbleGame() {
        const words = Array.from(document.querySelectorAll('.hidden-word')).map(el => ({
            english: el.dataset.word.toLowerCase(),
            polish: el.dataset.polish
        }));

        let guessedWords = [];
        let remainingLetters = splitWordsIntoLetters(words.map(word => word.english));
        let unguessedWords = words.map(word => word.english);

        const availableLettersDiv = document.getElementById('available-letters');
        const guessInput = document.getElementById('guess-input');
        const resultDiv = document.getElementById('game-result');
        const correctGuessesList = document.getElementById('correct-guesses');
        const invalidGuessesList = document.getElementById('invalid-guesses');
        const completionMessage = document.getElementById('completion-message');
        const guessFeedback = document.getElementById('guess-feedback');

        displayLetters(remainingLetters);

        displayPlaceholders();

        guessInput.addEventListener('input', function () {
            const currentGuess = guessInput.value.trim().toLowerCase();
            const feedbackIcon = guessFeedback;

            if (currentGuess && unguessedWords.some(word => word.startsWith(currentGuess))) {
                feedbackIcon.textContent = '✔️';
                feedbackIcon.classList.add('text-success');
                feedbackIcon.classList.remove('text-danger');
            } else {
                feedbackIcon.textContent = '❌';
                feedbackIcon.classList.add('text-danger');
                feedbackIcon.classList.remove('text-success');
            }
        });


        guessInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                submitGuess();
            }
        });

        document.getElementById('submit-guess').addEventListener('click', submitGuess);
        document.getElementById('generateNewBatch').addEventListener('click', function () {
            location.reload();
        });
        document.getElementById('hint-btn').addEventListener('click', giveHint);
        document.getElementById('giveUp').addEventListener('click', giveUp);

        function splitWordsIntoLetters(words) {
            let letters = words.join('').split('');
            return shuffleArray(letters);
        }

        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        }

        function displayLetters(letters) {
            availableLettersDiv.innerHTML = '';
            letters.forEach(letter => {
                const letterSpan = document.createElement('span');
                letterSpan.classList.add('letter');
                letterSpan.textContent = letter;
                availableLettersDiv.appendChild(letterSpan);
            });
        }

        function displayPlaceholders() {
            correctGuessesList.innerHTML = '';
            words.forEach(word => {
                if (!guessedWords.includes(word.english)) {
                    const placeholder = '_'.repeat(word.english.length);
                    addToList(correctGuessesList, placeholder, word.english);
                }
            });
        }

        function addToList(list, text, word = null) {
            const listItem = document.createElement('li');
            listItem.textContent = text;
            listItem.classList.add('list-group-item');
            if (word) listItem.dataset.word = word;
            list.appendChild(listItem);
        }

        function removeLetters(word) {
            const lettersToRemove = word.split('');
            remainingLetters = remainingLetters.filter(letter => {
                const index = lettersToRemove.indexOf(letter);
                if (index !== -1) {
                    lettersToRemove.splice(index, 1);
                    return false;
                }
                return true;
            });

            displayLetters(remainingLetters);
        }

        function submitGuess() {
            const guess = guessInput.value.trim().toLowerCase();
            guessInput.value = '';

            const word = words.find(w => w.english === guess);

            if (word && !guessedWords.includes(guess)) {
                guessedWords.push(guess);
                resultDiv.style.display = 'block';
                resultDiv.textContent = `Dobrze! Zgadłeś "${guess}".`;
                resultDiv.classList.remove('alert-danger');
                resultDiv.classList.add('alert-info');

                const placeholderItem = correctGuessesList.querySelector(`[data-word="${guess}"]`);
                if (placeholderItem) {
                    placeholderItem.textContent = guess;
                }

                removeLetters(guess);
                unguessedWords = unguessedWords.filter(w => w !== guess);

                if (guessedWords.length === words.length) {
                    displayCompletionMessage();
                }
            } else {
                resultDiv.style.display = 'block';
                resultDiv.textContent = `Źle. Spróbuj ponownie.`;
                resultDiv.classList.remove('alert-info');
                resultDiv.classList.add('alert-danger');
                addToList(invalidGuessesList, guess);
            }

            showFeedback(false);
        }

        function giveHint() {
            const unguessedWords = words.filter(word => !guessedWords.includes(word.english));
            if (unguessedWords.length > 0) {
                const randomWord = unguessedWords[Math.floor(Math.random() * unguessedWords.length)];
                resultDiv.style.display = 'block';
                resultDiv.textContent = `Podpowiedź: Polskie słowo to "${randomWord.polish}".`;
                resultDiv.classList.remove('alert-danger');
                resultDiv.classList.add('alert-info');
            }
        }

        function giveUp() {
            const unguessedWords = words.filter(word => !guessedWords.includes(word.english)).map(word =>
                word.english);
            resultDiv.style.display = 'block';
            resultDiv.textContent = `Game over. Nie zgadłeś: ${unguessedWords.join(', ')}.`;
            resultDiv.classList.remove('alert-info');
            resultDiv.classList.add('alert-danger');
        }

        function displayCompletionMessage() {
            completionMessage.style.display = 'block';
            completionMessage.textContent = `Gratulację! Zgadłeś wszystkie słowa!`;
        }

        function showFeedback(isCorrect) {
            if (isCorrect) {
                guessFeedback.textContent = '✔️';
                guessFeedback.classList.add('text-success');
                guessFeedback.classList.remove('text-danger');
            } else {
                guessFeedback.textContent = '❌';
                guessFeedback.classList.add('text-danger');
                guessFeedback.classList.remove('text-success');
            }
        }
    }
});
