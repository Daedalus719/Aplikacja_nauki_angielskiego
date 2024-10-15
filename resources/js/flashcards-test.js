document.addEventListener("DOMContentLoaded", function () {
    const polishWordElem = document.getElementById("polish-word");
    const answerInput = document.getElementById("answer");
    const prevButton = document.getElementById("prev");
    const nextButton = document.getElementById("next");
    const submitButton = document.getElementById("submit");

    let currentIndex = 0;
    let answers = Array(window.wordsData.length).fill('');

    const words = window.wordsData;

    function updateCard() {
        const currentWord = words[currentIndex];
        polishWordElem.textContent = currentWord.polish_word;
        answerInput.value = answers[currentIndex] || '';
        answerInput.dataset.index = currentIndex;

        prevButton.disabled = currentIndex === 0;
        nextButton.disabled = currentIndex === words.length - 1;
        submitButton.disabled = !allFieldsFilled();

        if (prevButton.disabled) {
            prevButton.classList.add("disabled");
        } else {
            prevButton.classList.remove("disabled");
        }

        if (nextButton.disabled && allFieldsFilled()) {
            submitButton.disabled = false;
        }

        if (nextButton.disabled) {
            nextButton.classList.add("disabled");
        } else {
            nextButton.classList.remove("disabled");
        }
    }

    function allFieldsFilled() {
        return answers.every(answer => answer.trim() !== '');
    }

    nextButton.addEventListener("click", function () {
        if (currentIndex < words.length - 1) {
            saveCurrentAnswer();
            currentIndex++;
            updateCard();
        }
    });

    prevButton.addEventListener("click", function () {
        if (currentIndex > 0) {
            saveCurrentAnswer();
            currentIndex--;
            updateCard();
        }
    });

    function saveCurrentAnswer() {
        const answerValue = answerInput.value.trim();
        answers[currentIndex] = answerValue;
    }

    submitButton.addEventListener("click", function () {
        if (!allFieldsFilled()) {
            alert('Proszę wypełnić wszystkie pola!');
            return;
        }

        let results = [];
        answers.forEach((userAnswer, idx) => {
            const correctAnswer = words[idx].english_word.trim().toLowerCase();
            results.push({
                word: words[idx].polish_word,
                userAnswer: userAnswer.toLowerCase(),
                correctAnswer: correctAnswer,
                isCorrect: userAnswer.toLowerCase() === correctAnswer,
            });
        });

        displayResults(results);
    });

    function displayResults(results) {
        const resultDiv = document.createElement('div');
        resultDiv.className = 'result-container mt-4';

        results.forEach(result => {
            const resultRow = document.createElement('div');
            resultRow.className = result.isCorrect ? 'result-correct' : 'result-incorrect';
            resultRow.innerHTML = `Polskie słowo: ${result.word}, Twoja odpowiedź: ${result.userAnswer},
                Poprawna odpowiedź: ${result.correctAnswer}`;
            resultDiv.appendChild(resultRow);
        });

        document.querySelector('.container').appendChild(resultDiv);
        submitButton.disabled = true;
    }

    answerInput.addEventListener("keydown", function (event) {
        if (event.key === "Enter") {
            if (currentIndex === words.length - 1) {
                submitButton.click();
            } else {
                nextButton.click();
            }
        }
    });

    answerInput.addEventListener("blur", saveCurrentAnswer);

    updateCard();
});
