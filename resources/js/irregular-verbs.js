document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#verbsTableBody');
    const addVerbForm = document.querySelector('#addVerbForm');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    if (!tableBody || !addVerbForm) {
        console.error('Nie mażna znaleźć tabeli lub elementu form.');
        return;
    }

    function enableEditMode(row, wordId) {
        const verb1st = row.querySelector('.verb-1st').textContent.trim();
        const verb2nd = row.querySelector('.verb-2nd').textContent.trim();
        const verb3rd = row.querySelector('.verb-3rd').textContent.trim();
        const polishTranslation = row.querySelector('.polish-translation').textContent.trim();

        row.querySelector('.verb-1st').innerHTML = `<input type="text" class="form-control" value="${verb1st}">`;
        row.querySelector('.verb-2nd').innerHTML = `<input type="text" class="form-control" value="${verb2nd}">`;
        row.querySelector('.verb-3rd').innerHTML = `<input type="text" class="form-control" value="${verb3rd}">`;
        row.querySelector('.polish-translation').innerHTML = `<input type="text" class="form-control" value="${polishTranslation}">`;

        row.querySelector(`button[data-word-id="${wordId}"].edit-btn`).style.display = 'none';
        row.querySelector(`button[data-word-id="${wordId}"].save-btn`).style.display = 'inline-block';
    }

    function saveVerbChanges(row, wordId) {
        const updatedVerb1st = row.querySelector('.verb-1st input').value.trim();
        const updatedVerb2nd = row.querySelector('.verb-2nd input').value.trim();
        const updatedVerb3rd = row.querySelector('.verb-3rd input').value.trim();
        const updatedPolishTranslation = row.querySelector('.polish-translation input').value.trim();

        fetch(`/irregular-verbs/${wordId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({
                verb_1st_form: updatedVerb1st,
                verb_2nd_form: updatedVerb2nd,
                verb_3rd_form: updatedVerb3rd,
                polish_translation: updatedPolishTranslation,
            }),
        })
            .then(response => response.json())
            .then(data => {
                row.querySelector('.verb-1st').textContent = updatedVerb1st;
                row.querySelector('.verb-2nd').textContent = updatedVerb2nd;
                row.querySelector('.verb-3rd').textContent = updatedVerb3rd;
                row.querySelector('.polish-translation').textContent = updatedPolishTranslation;

                row.querySelector(`button[data-word-id="${wordId}"].edit-btn`).style.display = 'inline-block';
                row.querySelector(`button[data-word-id="${wordId}"].save-btn`).style.display = 'none';

                showMessage('success', 'Wpis został z powodzeniem dodany do bazy!');
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('error', 'Nie udało się dodać wpisu do bazy!');
            });
    }

    tableBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('edit-btn')) {
            const wordId = e.target.getAttribute('data-word-id');
            const row = e.target.closest('tr');
            enableEditMode(row, wordId);
        }

        if (e.target.classList.contains('save-btn')) {
            const wordId = e.target.getAttribute('data-word-id');
            const row = e.target.closest('tr');
            saveVerbChanges(row, wordId);
        }
    });

    addVerbForm.addEventListener('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(addVerbForm);

        fetch(addVerbForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: formData,
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                let newRow = document.createElement('tr');
                newRow.innerHTML = `
                <td class="verb-id" style="display:none;">${data.verb.id}</td>
                <td><button class="btn btn-sm btn-primary tts-btn">🔊</button></td>
                <td class="verb-1st">${data.verb.verb_1st_form}</td>
                <td class="verb-2nd">${data.verb.verb_2nd_form}</td>
                <td class="verb-3rd">${data.verb.verb_3rd_form}</td>
                <td class="polish-translation">${data.verb.polish_translation}</td>
                <td>
                    <button class="btn btn-sm btn-outline-secondary edit-btn" data-word-id="${data.verb.id}">Edytuj</button>
                    <button class="btn btn-sm btn-outline-success save-btn" data-word-id="${data.verb.id}" style="display: none;">Zapisz</button>
                    <form action="/irregular-verbs/${data.verb.id}" method="POST" style="display:inline;" class="delete-form">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="${csrfToken}">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Usuń</button>
                    </form>
                </td>
            `;
                tableBody.appendChild(newRow);
                addVerbForm.reset();
                showMessage('success', data.message);
            })
            .catch(error => {
                error.response.json().then(errData => {
                    console.error('Error Data:', errData);
                    showMessage('error', 'Nie udało się dodać wpisu do bazy!');
                }).catch(() => {
                    console.error('Unknown error occurred:', error);
                    showMessage('error', 'Wystąpił niespodziewany błąd.');
                });
            });

    });



    function speak(text) {
        const sanitizedWord = text.replace(/[\/\\$$]/g, '   ');

        const utterance = new SpeechSynthesisUtterance(sanitizedWord.trim());
        utterance.lang = 'en-US';
        speechSynthesis.speak(utterance);
    }


    tableBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('tts-btn')) {
            const row = e.target.closest('tr');
            const verb1st = row.querySelector('.verb-1st').textContent.trim();
            const verb2nd = row.querySelector('.verb-2nd').textContent.trim();
            const verb3rd = row.querySelector('.verb-3rd').textContent.trim();


            speak(`${verb1st}, ${verb2nd}, ${verb3rd}`);
        }
    });

    function showMessage(type, message) {
        const messageBox = document.querySelector('#message-box');
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';

        const alertDiv = document.createElement('div');
        alertDiv.classList.add('alert', alertClass, 'alert-dismissible', 'fade', 'show');
        alertDiv.setAttribute('role', 'alert');
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        `;

        messageBox.appendChild(alertDiv);

        setTimeout(function () {
            alertDiv.style.transition = 'opacity 0.5s';
            alertDiv.style.opacity = '0';

            setTimeout(function () {
                alertDiv.remove();
            }, 500);
        }, 3000);
    }

    document.addEventListener('submit', function (e) {
        if (e.target.classList.contains('delete-form')) {
            e.preventDefault();
            const form = e.target;
            const actionUrl = form.action;

            fetch(actionUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
            })
                .then(response => response.json())
                .then(data => {
                    const row = form.closest('tr');
                    row.remove();
                    showMessage('success', data.message);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('error', 'Nie udało się usunąć wpisu!');
                });
        }
    });
});


document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#verbsTableBody');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let deleteForm;

    let deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));

    tableBody.addEventListener('click', function (e) {
        if (e.target.closest('form') && e.target.classList.contains('btn-outline-danger')) {
            e.preventDefault();
            deleteForm = e.target.closest('form');
            deleteModal.show();
        }
    });

    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    confirmDeleteBtn.addEventListener('click', function () {
        if (deleteForm) {
            const actionUrl = deleteForm.action;

            fetch(actionUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
            })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then((data) => {
                            throw new Error(data.message || 'Delete failed');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    const row = deleteForm.closest('tr');
                    row.remove();
                    showMessage('success', data.message);
                    deleteModal.hide();
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('error', error.message || 'Nie udało się usunąć wpisu!');
                    deleteModal.hide();
                });
        }
    });

});
