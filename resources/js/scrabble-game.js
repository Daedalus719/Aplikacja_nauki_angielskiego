document.addEventListener('DOMContentLoaded', function () {
    const words = Array.from(document.querySelectorAll('.hidden-word')).map(el => ({
        english: el.dataset.word.toLowerCase(),
        polish: el.dataset.polish
    }));
    const availableLettersDiv = document.getElementById('available-letters');
    const guessInput = document.getElementById('guess-input');
    const scoreElement = document.getElementById('score');
    const resultDiv = document.getElementById('game-result');
    const correctGuessesList = document.getElementById('correct-guesses');
    const invalidGuessesList = document.getElementById('invalid-guesses');
    const completionMessage = document.getElementById('completion-message');
    let score = 0;
    let guessedWords = [];
    let remainingLetters = splitWordsIntoLetters(words.map(word => word.english));

    displayLetters(remainingLetters);

    guessInput.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            submitGuess();
        }
    });

    document.getElementById('submit-guess').addEventListener('click', submitGuess);
    document.getElementById('generateNewBatch').addEventListener('click', generateNewBatch);
    document.getElementById('hint-btn').addEventListener('click', giveHint);
    document.getElementById('giveUp').addEventListener('click', giveUp);

    function splitWordsIntoLetters(words) {
        let letters = words.join('').split('');
        return shuffleArray(letters);
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

    function shuffleArray(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
        return array;
    }

    function submitGuess() {
        const guess = guessInput.value.trim().toLowerCase();
        guessInput.value = '';

        const word = words.find(w => w.english === guess);

        if (word && !guessedWords.includes(guess)) {
            guessedWords.push(guess);
            score += 10;
            updateScore();
            resultDiv.style.display = 'block';
            resultDiv.textContent = `Dobrze! Zgadłeś "${guess}".`;
            resultDiv.classList.remove('alert-danger');
            resultDiv.classList.add('alert-info');
            addToList(correctGuessesList, guess);
            removeLetters(guess);

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

    function updateScore() {
        scoreElement.textContent = score;
    }

    function addToList(list, guess) {
        const listItem = document.createElement('li');
        listItem.textContent = guess;
        listItem.classList.add('list-group-item');
        list.appendChild(listItem);
    }

    function generateNewBatch() {
        resultDiv.style.display = 'none';
        score = 0;
        updateScore();
        remainingLetters = splitWordsIntoLetters(words.map(word => word.english));
        displayLetters(remainingLetters);
        resultDiv.style.display = 'none';

        correctGuessesList.innerHTML = '';
        invalidGuessesList.innerHTML = '';
        guessedWords = [];
        completionMessage.style.display = 'none';
    }

    function giveUp() {
        const unguessedWords = words.filter(word => !guessedWords.includes(word.english)).map(word => word.english);
        resultDiv.style.display = 'block';
        resultDiv.textContent = `Game over. Nie zgadłeś: ${unguessedWords.join(', ')}.`;
        resultDiv.classList.remove('alert-info');
        resultDiv.classList.add('alert-danger');
    }

    function displayCompletionMessage() {
        completionMessage.style.display = 'block';
        completionMessage.textContent = `Gratulację! Zgadłeś wszystkie słowa!`;
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
});
