export function showMessage(type, message) {
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
