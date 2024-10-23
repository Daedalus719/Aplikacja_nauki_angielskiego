<x-app-layout>
    @section('title', 'Wszystkie zadania dla ' . $section->title)

    <div class="container">
        <h1 class="main-text mt-3 mb-2">Wszystkie zadania dla czasu {{ $section->title }}</h1>

        @foreach ($tasks as $task)
            <div class="task-item mt-3" data-task-id="{{ $task->id }}">
                <h5 class="label-color">Zadanie ID: {{ $task->id }}</h5>
                <div class="rich-text-editor label-color" contenteditable="false">{!! $task->text !!}</div>

                <button class="btn btn-primary edit-task-btn mt-2">Edytuj</button>
                <button class="btn btn-danger delete-task-btn mt-2" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal" data-task-id="{{ $task->id }}">Usuń</button>
            </div>

            <hr>
        @endforeach

        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Potwierdź usunięcie</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">Czy na pewno chcesz usunąć to zadanie?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Usuń</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .highlight-editable {
            border: 2px dashed #007bff;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let taskToDelete = null;

            document.querySelectorAll('.delete-task-btn').forEach(button => {
                button.addEventListener('click', function () {
                    taskToDelete = this.getAttribute('data-task-id');
                });
            });

            document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
                if (taskToDelete) {
                    fetch(`/tasks/${taskToDelete}/delete`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.querySelector(`.task-item[data-task-id="${taskToDelete}"]`).remove();
                            const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteConfirmationModal'));
                            deleteModal.hide();
                        }
                    })
                    .catch(error => console.error('Error deleting task:', error));
                }
            });

            document.querySelectorAll('.edit-task-btn').forEach(button => {
                button.addEventListener('click', function () {
                    const taskItem = this.closest('.task-item');
                    const taskId = taskItem.getAttribute('data-task-id');
                    const richTextEditor = taskItem.querySelector('.rich-text-editor');

                    const isEditable = richTextEditor.getAttribute('contenteditable') === 'true';
                    richTextEditor.setAttribute('contenteditable', !isEditable);

                    if (isEditable) {
                        const updatedText = richTextEditor.innerHTML;
                        fetch(`/tasks/${taskId}/update`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            },
                            body: JSON.stringify({ text: updatedText }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            alert(data.message);
                            richTextEditor.classList.remove('highlight-editable');
                        })
                        .catch(error => console.error('Error updating task:', error));
                    } else {
                        richTextEditor.classList.add('highlight-editable');
                    }

                    this.textContent = isEditable ? 'Edytuj' : 'Zapisz';
                });
            });
        });
    </script>
</x-app-layout>
