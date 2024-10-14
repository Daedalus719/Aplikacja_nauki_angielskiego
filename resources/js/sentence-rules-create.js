document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('addRuleForm');
    const messageBox = document.getElementById('message');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        fetch('/sentence-rules/store-rule', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                form.reset();
                showMessage('success', 'Reguła została dodana pomyślnie!');
            } else {
                showMessage('danger', 'Wystąpił błąd przy dodawaniu reguły.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('danger', 'Wystąpił błąd serwera.');
        });
    });

    function showMessage(type, message) {
        messageBox.style.display = 'block';
        messageBox.classList.add('alert-' + type);
        messageBox.textContent = message;

        setTimeout(() => {
            messageBox.style.display = 'none';
            messageBox.classList.remove('alert-' + type);
        }, 4000);
    }
});
