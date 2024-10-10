document.addEventListener('DOMContentLoaded', function () {
    const verbs = window.verbData; // Get verbs data from global window object
    const numberOfWords = 5;
    let selectedVerbs = [];

    function generateVerbs() {
        selectedVerbs = [];
        let usedIndices = [];

        const tableBody = document.getElementById('verbsTableBody');
        tableBody.innerHTML = '';

        for (let i = 0; i < numberOfWords; i++) {
            let randomIndex;
            do {
                randomIndex = Math.floor(Math.random() * verbs.length);
            } while (usedIndices.includes(randomIndex));
            usedIndices.push(randomIndex);

            const verb = verbs[randomIndex];
            selectedVerbs.push(verb);

            // Change 'form_1', 'form_2', 'form_3' to the actual field names from your $verbs data
            const givenFormIndex = Math.floor(Math.random() * 3) + 1;

            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    ${givenFormIndex === 1 ? `<strong>${verb.form_1}</strong>` : `<input type="text" class="form-control" data-correct="${verb.form_1}" placeholder="1st form">`}
                </td>
                <td>
                    ${givenFormIndex === 2 ? `<strong>${verb.form_2}</strong>` : `<input type="text" class="form-control" data-correct="${verb.form_2}" placeholder="2nd form">`}
                </td>
                <td>
                    ${givenFormIndex === 3 ? `<strong>${verb.form_3}</strong>` : `<input type="text" class="form-control" data-correct="${verb.form_3}" placeholder="3rd form">`}
                </td>
                <td>${verb.polish_translation}</td>
            `;
            tableBody.appendChild(row);
        }
    }

    function checkAnswers() {
        const inputs = document.querySelectorAll('#verbsTableBody input[type="text"]');
        let correctCount = 0;
        let totalCount = inputs.length;

        inputs.forEach(input => {
            const correctAnswer = input.getAttribute('data-correct').toLowerCase();
            const userAnswer = input.value.trim().toLowerCase();

            input.classList.remove('is-valid', 'is-invalid');
            let feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains('text-danger')) {
                feedback.remove();
            }

            if (userAnswer !== "" && userAnswer === correctAnswer) {
                correctCount++;
                input.classList.add('is-valid');
            } else {
                input.classList.add('is-invalid');
                if (userAnswer !== correctAnswer) {
                    let correctFeedback = document.createElement('div');
                    correctFeedback.classList.add('text-danger');
                    correctFeedback.textContent = `Correct: ${correctAnswer}`;
                    input.parentNode.appendChild(correctFeedback);
                }
            }
        });

        alert(`You got ${correctCount} out of ${totalCount} correct!`);
    }

    document.getElementById('checkAnswersBtn').addEventListener('click', checkAnswers);
    document.getElementById('tryAgainBtn').addEventListener('click', generateVerbs);

    generateVerbs();
});
