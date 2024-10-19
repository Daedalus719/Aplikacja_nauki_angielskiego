<x-app-layout>
    @section('title', 'Edit Task')

    <div class="container">
        <h1>Edytuj zadanie</h1>

        <form action="{{ route('tasks.update', ['section_id' => $task->section_id, 'id' => $task->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="text">Treść</label>
                <input type="text" name="text" value="{{ $task->text }}" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Zapisz zmiany</button>
        </form>


    </div>
</x-app-layout>
