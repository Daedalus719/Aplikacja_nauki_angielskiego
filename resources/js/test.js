document.addEventListener('DOMContentLoaded', function () {
    let wordsData = window.wordsData;

    function generateMissingLettersTest() {
        let form = document.getElementById('missingLettersForm');
        form.innerHTML = '';
        let selectedWords = wordsData.sort(() => 0.5 - Math.random()).slice(0, 5);

        selectedWords.forEach(word => {
            let vowels = ['a', 'e', 'i', 'o', 'u'];
            let wordStr = word.english_word;


            let letters = wordStr.split('');
            let indices = letters
                .map((letter, index) => vowels.includes(letter.toLowerCase()) ? index : -1)
                .filter(index => index !== -1);

            let randomIndex = indices[Math.floor(Math.random() * indices.length)];
            let correctLetter = letters[randomIndex];
            letters[randomIndex] = `<input type="text" class="form-control d-inline-block w-auto" name="word[${word.id}]" maxlength="1" required>`;
            let wordWithBlank = letters.join('');

            form.innerHTML += `
                        <div class="mb-3">
                            <label class="form-label">${wordWithBlank} (${word.polish_word})</label>
                            <input type="hidden" name="correct_letter[${word.id}]" value="${correctLetter}">
                            <div class="correct-answer text-danger" style="display:none;">{Poprawna odpowiedź}: ${correctLetter}</div>
                        </div>
                    `;
        });
    }

    function generateTranslationTest() {
        let form = document.getElementById('translationForm');
        form.innerHTML = '';
        let selectedWords = wordsData.sort(() => 0.5 - Math.random()).slice(0, 5);

        selectedWords.forEach(word => {
            form.innerHTML += `
                <div class="mb-3">
                    <label for="translation-${word.id}" class="form-label">Translate to English: ${word.polish_word}</label>
                    <input type="text" class="form-control" id="translation-${word.id}" name="translation[${word.id}]" required>
                    <input type="hidden" name="correct_translation[${word.id}]" value="${word.english_word}">
                    <div class="correct-answer text-danger" style="display:none;">Poprawna odpowiedź: ${word.english_word}</div>
                </div>
            `;
        });
    }

    function checkMissingLetters() {
        let form = document.getElementById('missingLettersForm');
        let inputs = form.querySelectorAll('input[type="text"]');
        let correct = 0;
        let total = inputs.length;
        let resultMessage = document.getElementById('resultMessage');

        resultMessage.style.display = 'block';
        resultMessage.innerHTML = '';

        inputs.forEach(input => {
            let wordId = input.name.match(/\d+/)[0];
            let correctLetter = form.querySelector(`input[name="correct_letter[${wordId}]"]`).value;
            let correctAnswerDiv = input.closest('.mb-3').querySelector('.correct-answer');

            if (input.value.toLowerCase() === correctLetter.toLowerCase()) {
                correct++;
                input.classList.add('is-valid');
                input.classList.remove('is-invalid');
                correctAnswerDiv.style.display = 'none';
            } else {
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
                correctAnswerDiv.style.display = 'block';
            }
        });

        resultMessage.innerHTML = `Uzyskałeś <strong>${correct}</strong> z <strong>${total}</strong> poprawnych odpowiedzi!`;

        if (correct >= 0 && correct <= 2) {
            resultMessage.style.color = 'red';
        } else if (correct >= 3 && correct <= 5) {
            resultMessage.style.color = 'green';
        }

        resultMessage.style.fontWeight = 'bold';
    }

    document.getElementById('tryAgainMissingLetters').addEventListener('click', function () {
        let resultMessage = document.getElementById('resultMessage');
        resultMessage.style.display = 'none';
        resultMessage.innerHTML = '';
    });




    function checkTranslations() {
        let form = document.getElementById('translationForm');
        let inputs = form.querySelectorAll('input[type="text"]');
        let correct = 0;
        let total = inputs.length;
        let resultMessage = document.getElementById('translationResultMessage');

        resultMessage.style.display = 'block';
        resultMessage.innerHTML = '';

        inputs.forEach(input => {
            let wordId = input.name.match(/\d+/)[0];
            let correctWord = form.querySelector(`input[name="correct_translation[${wordId}]"]`).value;
            let correctAnswerDiv = input.closest('.mb-3').querySelector('.correct-answer');

            if (input.value.toLowerCase() === correctWord.toLowerCase()) {
                correct++;
                input.classList.add('is-valid');
                input.classList.remove('is-invalid');
                correctAnswerDiv.style.display = 'none';
            } else {
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
                correctAnswerDiv.style.display = 'block';
            }
        });

        resultMessage.innerHTML = `Uzyskałeś <strong>${correct}</strong> z <strong>${total}</strong> poprawnych odpowiedzi!`;

        if (correct >= 0 && correct <= 2) {
            resultMessage.style.color = 'red';
        } else if (correct >= 3 && correct <= 5) {
            resultMessage.style.color = 'green';
        }

        resultMessage.style.fontWeight = 'bold';
    }

    document.getElementById('tryAgainTranslation').addEventListener('click', function () {
        let resultMessage = document.getElementById('translationResultMessage');
        resultMessage.style.display = 'none';
        resultMessage.innerHTML = '';
    });


    function toggleTestContent(contentId) {
        document.getElementById('testCards').style.display = 'none';
        document.querySelectorAll('.test-content').forEach(content => content.style.display = 'none');
        document.getElementById(contentId).style.display = 'block';
    }

    document.querySelectorAll('.back-btn').forEach(button => {
        button.addEventListener('click', function () {
            document.getElementById('testCards').style.display = 'flex';
            document.querySelectorAll('.test-content').forEach(content => content.style.display = 'none');
        });
    });


    document.querySelector('.test-card[data-test="missing-letters"]').addEventListener('click', function () {
        generateMissingLettersTest();
        toggleTestContent('missingLettersContent');
    });

    document.querySelector('.test-card[data-test="translation-test"]').addEventListener('click', function () {
        generateTranslationTest();
        toggleTestContent('translationTestContent');
    });

    document.getElementById('checkMissingLetters').addEventListener('click', checkMissingLetters);
    document.getElementById('checkTranslation').addEventListener('click', checkTranslations);

    document.getElementById('tryAgainMissingLetters').addEventListener('click', generateMissingLettersTest);
    document.getElementById('tryAgainTranslation').addEventListener('click', generateTranslationTest);
});

