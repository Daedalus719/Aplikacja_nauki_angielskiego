document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.edit-user').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            toggleEditMode(userId, true);
        });
    });

    document.querySelectorAll('.cancel-edit').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            toggleEditMode(userId, false);
        });
    });

    document.querySelectorAll('.save-user').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            saveUser(userId);
        });
    });

    document.querySelectorAll('.delete-user').forEach(button => {
        button.addEventListener('click', function () {
            const userId = this.getAttribute('data-id');
            deleteUser(userId);
        });
    });
});

function toggleEditMode(userId, isEditMode) {
    const nameField = document.querySelector(`.user-name[data-id="${userId}"]`);
    const emailField = document.querySelector(`.user-email[data-id="${userId}"]`);
    const roleField = document.querySelector(`.user-role[data-id="${userId}"]`);

    const editButton = document.querySelector(`.edit-user[data-id="${userId}"]`);
    const saveButton = document.querySelector(`.save-user[data-id="${userId}"]`);
    const cancelButton = document.querySelector(`.cancel-edit[data-id="${userId}"]`);

    if (isEditMode) {
        nameField.disabled = false;
        emailField.disabled = false;
        roleField.disabled = false;
        editButton.classList.add('d-none');
        saveButton.classList.remove('d-none');
        cancelButton.classList.remove('d-none');
    } else {
        nameField.disabled = true;
        emailField.disabled = true;
        roleField.disabled = true;
        editButton.classList.remove('d-none');
        saveButton.classList.add('d-none');
        cancelButton.classList.add('d-none');
    }
}

function saveUser(userId) {
    const name = document.querySelector(`.user-name[data-id="${userId}"]`).value;
    const email = document.querySelector(`.user-email[data-id="${userId}"]`).value;
    const usertype = document.querySelector(`.user-role[data-id="${userId}"]`).value;

    fetch(`/admin/user/update/${userId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            name: name,
            email: email,
            usertype: usertype
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('User updated successfully.');
            toggleEditMode(userId, false);
        } else {
            alert('Failed to update user.');
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user?')) {
        fetch(`/admin/user/delete/${userId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('User deleted successfully.');
                document.querySelector(`tr[data-id="${userId}"]`).remove();
            } else {
                alert('Failed to delete user.');
            }
        })
        .catch(error => console.error('Error:', error));
    }
}
