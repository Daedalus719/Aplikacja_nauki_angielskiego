document.addEventListener('DOMContentLoaded', function () {
    const wordSetupForm = document.getElementById('wordSetupForm');
    const setupModal = document.getElementById('setup-modal');
    const gameArea = document.getElementById('game-area');
    const startGameButton = document.getElementById('start-game');

    wordSetupForm.addEventListener('submit', function (event) {
        event.preventDefault();
        startGame();
    });

    startGameButton.addEventListener('click', function () {
        startGame();
    });

    function startGame() {
        const numWords = document.getElementById('numWords').value;

        const wordTypes = Array.from(document.querySelectorAll('input[name="wordTypes[]"]:checked'))
            .map(checkbox => checkbox.value);

        setupModal.style.display = 'none';
        gameArea.style.display = 'block';

        console.log('Uruchamianie gry z ' + numWords + ' słowami oraz następujący typami słów: ' + wordTypes.join(', '));

        fetch('/start-scrabble-game', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                numWords: numWords,
                wordTypes: wordTypes
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Słowa uzyskane z bazy:', data);
            initializeGame(data.words);
        })
        .catch(error => {
            console.error('Błąd podczas pozyskiwania danych:', error);
        });
    }

    function initializeGame(words) {
        console.log('Inicjalizowanie z poniższymi słowami:', words);

        const lettersContainer = document.getElementById('letters-container');
        const guessedWordsContainer = document.getElementById('guessed-words');
        const incorrectWordsContainer = document.getElementById('incorrect-words');

        lettersContainer.innerHTML = '';
        guessedWordsContainer.innerHTML = '';
        incorrectWordsContainer.innerHTML = '';

        words.forEach(word => {
            const letters = word.split('');
            letters.forEach(letter => {
                const letterElem = document.createElement('div');
                letterElem.classList.add('letter');
                letterElem.innerText = letter;
                lettersContainer.appendChild(letterElem);
            });
        });
    }
});
