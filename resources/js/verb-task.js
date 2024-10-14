document.addEventListener('DOMContentLoaded', function () {
    const verbs = window.verbData;
    const numberOfWords = 5;

    function getRandomVerbs() {
        const selectedVerbs = [];
        const tableBody = document.getElementById('verbsTableBody');
        tableBody.innerHTML = '';

        while (selectedVerbs.length < numberOfWords) {
            const randomIndex = Math.floor(Math.random() * verbs.length);
            const verb = verbs[randomIndex];

            if (!selectedVerbs.includes(verb)) {
                selectedVerbs.push(verb);

                const givenFormIndex = Math.floor(Math.random() * 3) + 1;

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        ${givenFormIndex === 1 ? `<strong>${verb.verb_1st_form}</strong>` : `<input type="text" class="form-control" data-correct="${verb.verb_1st_form}" placeholder="I forma">`}
                    </td>
                    <td>
                        ${givenFormIndex === 2 ? `<strong>${verb.verb_2nd_form}</strong>` : `<input type="text" class="form-control" data-correct="${verb.verb_2nd_form}" placeholder="II forma">`}
                    </td>
                    <td>
                        ${givenFormIndex === 3 ? `<strong>${verb.verb_3rd_form}</strong>` : `<input type="text" class="form-control" data-correct="${verb.verb_3rd_form}" placeholder="III forma">`}
                    </td>
                    <td>${verb.polish_translation}</td>
                `;
                tableBody.appendChild(row);
            }
        }
    }

    function checkAnswers() {
        const inputs = document.querySelectorAll('#verbsTableBody input[type="text"]');
        let correctCount = 0;
        let totalCount = inputs.length;

        inputs.forEach(input => {
            const correctAnswers = input.getAttribute('data-correct').toLowerCase().split(', ');
            const userAnswer = input.value.trim().toLowerCase();

            input.classList.remove('is-valid', 'is-invalid');
            let feedback = input.nextElementSibling;
            if (feedback && feedback.classList.contains('text-danger')) {
                feedback.remove();
            }

            if (userAnswer !== "" && correctAnswers.includes(userAnswer)) {
                correctCount++;
                input.classList.add('is-valid');
            } else {
                input.classList.add('is-invalid');
                if (!correctAnswers.includes(userAnswer)) {
                    let correctFeedback = document.createElement('div');
                    correctFeedback.classList.add('text-danger');
                    correctFeedback.textContent = `Poprawna odpowiedź: ${correctAnswers.join(', ')}`;
                    input.parentNode.appendChild(correctFeedback);
                }
            }
        });

        const resultMessage = document.getElementById('resultMessage');
        resultMessage.style.display = 'block';

        if (correctCount < totalCount) {
            resultMessage.textContent = `Uzyskałeś ${correctCount} z ${totalCount} poprawnych odpowiedzi!`;
            resultMessage.style.color = 'red';
            resultMessage.style.fontWeight = 'bold';
        } else {
            resultMessage.textContent = `Gratulacje! Uzyskałeś ${correctCount} z ${totalCount} poprawnych odpowiedzi!`;
            resultMessage.style.color = 'green';
            resultMessage.style.fontWeight = 'bold';
        }
    }

    document.getElementById('tryAgainBtn').addEventListener('click', function() {
        const inputs = document.querySelectorAll('#verbsTableBody input[type="text"]');
        inputs.forEach(input => {
            input.value = '';
            input.classList.remove('is-valid', 'is-invalid');
        });

        const resultMessage = document.getElementById('resultMessage');
        resultMessage.style.display = 'none';
    });

    document.getElementById('checkAnswersBtn').addEventListener('click', checkAnswers);
    document.getElementById('tryAgainBtn').addEventListener('click', getRandomVerbs);

    getRandomVerbs();
});
