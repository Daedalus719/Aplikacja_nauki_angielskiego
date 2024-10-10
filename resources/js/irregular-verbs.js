// resources/js/irregular-verbs.js
document.addEventListener('DOMContentLoaded', function () {
    const tableBody = document.querySelector('#verbsTable tbody');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Edit button functionality
    tableBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('edit-btn')) {
            let row = e.target.closest('tr');
            let verbId = row.dataset.id;

            let verb1st = row.querySelector('.verb-1st').textContent;
            let verb2nd = row.querySelector('.verb-2nd').textContent;
            let verb3rd = row.querySelector('.verb-3rd').textContent;
            let polishTranslation = row.querySelector('.polish-translation').textContent;

            // Make the fields editable
            row.querySelector('.verb-1st').innerHTML = `<input type="text" class="form-control" value="${verb1st}">`;
            row.querySelector('.verb-2nd').innerHTML = `<input type="text" class="form-control" value="${verb2nd}">`;
            row.querySelector('.verb-3rd').innerHTML = `<input type="text" class="form-control" value="${verb3rd}">`;
            row.querySelector('.polish-translation').innerHTML = `<input type="text" class="form-control" value="${polishTranslation}">`;

            // Change Edit button to Save button
            e.target.textContent = 'Save';
            e.target.classList.remove('edit-btn');
            e.target.classList.add('save-btn');
        }

        // Save button functionality
        if (e.target.classList.contains('save-btn')) {
            let row = e.target.closest('tr');
            let verbId = row.dataset.id;

            let verb1st = row.querySelector('.verb-1st input').value;
            let verb2nd = row.querySelector('.verb-2nd input').value;
            let verb3rd = row.querySelector('.verb-3rd input').value;
            let polishTranslation = row.querySelector('.polish-translation input').value;

            fetch(`/irregular-verbs/${verbId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({
                    verb_1st_form: verb1st,
                    verb_2nd_form: verb2nd,
                    verb_3rd_form: verb3rd,
                    polish_translation: polishTranslation,
                }),
            })
            .then(response => response.json())
            .then(data => {
                // Update the row with the new values
                row.querySelector('.verb-1st').textContent = verb1st;
                row.querySelector('.verb-2nd').textContent = verb2nd;
                row.querySelector('.verb-3rd').textContent = verb3rd;
                row.querySelector('.polish-translation').textContent = polishTranslation;

                // Change Save button back to Edit button
                e.target.textContent = 'Edit';
                e.target.classList.remove('save-btn');
                e.target.classList.add('edit-btn');

                alert('Verb updated successfully');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update the verb');
            });
        }

        // Delete button functionality
        if (e.target.classList.contains('delete-btn')) {
            let row = e.target.closest('tr');
            let verbId = row.dataset.id;

            if (confirm('Are you sure you want to delete this verb?')) {
                fetch(`/irregular-verbs/${verbId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                    },
                })
                .then(response => response.json())
                .then(data => {
                    row.remove();
                    alert('Verb deleted successfully');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to delete the verb');
                });
            }
        }
    });

    // Form submission for adding new verbs
    const addVerbForm = document.getElementById('addVerbForm');
    addVerbForm.addEventListener('submit', function (e) {
        e.preventDefault();

        let formData = new FormData(addVerbForm);
        fetch(addVerbForm.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            let newRow = document.createElement('tr');
            newRow.dataset.id = data.verb.id;
            newRow.innerHTML = `
                <td class="verb-1st">${data.verb.verb_1st_form}</td>
                <td class="verb-2nd">${data.verb.verb_2nd_form}</td>
                <td class="verb-3rd">${data.verb.verb_3rd_form}</td>
                <td class="polish-translation">${data.verb.polish_translation}</td>
                <td>
                    <button class="btn btn-sm btn-warning edit-btn">Edit</button>
                    <button class="btn btn-sm btn-danger delete-btn">Delete</button>
                </td>
            `;
            tableBody.appendChild(newRow);
            addVerbForm.reset();
            alert('Verb added successfully');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to add the verb');
        });
    });
});
