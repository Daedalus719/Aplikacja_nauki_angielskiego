document.addEventListener('DOMContentLoaded', function () {
    const englishWordElement = document.querySelector('#english-word');
    const optionButtons = document.querySelectorAll('.option-btn');
    const resultMessage = document.querySelector('#result-message');
    const scoreElement = document.querySelector('#score');
    let score = 0;

    function loadWord() {
        fetch('/games/random-words')
            .then(response => response.json())
            .then(data => {
                const correctWord = data.correct_word;
                const incorrectWord = data.incorrect_word;

                englishWordElement.textContent = correctWord.english_word;

                if (Math.random() > 0.5) {
                    optionButtons[0].textContent = correctWord.polish_word;
                    optionButtons[1].textContent = incorrectWord.polish_word;
                    optionButtons[0].dataset.answer = 'true';
                    optionButtons[1].dataset.answer = 'false';
                } else {
                    optionButtons[0].textContent = incorrectWord.polish_word;
                    optionButtons[1].textContent = correctWord.polish_word;
                    optionButtons[0].dataset.answer = 'false';
                    optionButtons[1].dataset.answer = 'true';
                }
            })
            .catch(error => {
                console.error('Wystąpił błąd podczas pobierania danych:', error);
            });
    }

    function updateScore(isCorrect) {
        if (isCorrect) {
            score += 10;
        }
        scoreElement.textContent = score;
    }

    optionButtons.forEach(button => {
        button.addEventListener('click', function () {
            const isCorrect = this.dataset.answer === 'true';
            if (isCorrect) {
                resultMessage.innerHTML = '<strong>Poprawna odpowiedź!</strong>';
                resultMessage.classList.add('text-success');
                updateScore(true);
            } else {
                resultMessage.innerHTML = '<strong>Niepoprawna odpowiedź!</strong>';
                resultMessage.classList.add('text-danger');
                updateScore(false);
            }

            setTimeout(() => {
                resultMessage.textContent = '';
                resultMessage.classList.remove('text-success', 'text-danger');
                loadWord();
            }, 2000);
        });
    });


    loadWord();
});
