document.addEventListener('DOMContentLoaded', function () {
    function checkMissingLetters() {
        let form = document.getElementById('missingLettersForm');
        let inputs = form.querySelectorAll('input[type="text"]');
        let correct = 0;
        let total = inputs.length;

        inputs.forEach(input => {
            let wordId = input.name.match(/\d+/)[0];
            let correctLetter = form.querySelector(`input[name="correct_letter[${wordId}]"]`).value;
            if (input.value.toLowerCase() === correctLetter.toLowerCase()) {
                correct++;
                input.classList.add('is-valid');
                input.classList.remove('is-invalid');
            } else {
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
            }
        });

        alert(`You got ${correct} out of ${total} correct!`);
    }

    function checkTranslations() {
        let form = document.getElementById('translationForm');
        let inputs = form.querySelectorAll('input[type="text"]');
        let correct = 0;
        let total = inputs.length;

        inputs.forEach(input => {
            let wordId = input.name.match(/\d+/)[0];
            let correctWord = form.querySelector(`input[name="correct_translation[${wordId}]"]`).value;
            if (input.value.toLowerCase() === correctWord.toLowerCase()) {
                correct++;
                input.classList.add('is-valid');
                input.classList.remove('is-invalid');
            } else {
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
            }
        });

        alert(`You got ${correct} out of ${total} correct!`);
    }

    document.querySelector('#missingLettersForm button[type="button"]').addEventListener('click', checkMissingLetters);
    document.querySelector('#translationForm button[type="button"]').addEventListener('click', checkTranslations);
});
