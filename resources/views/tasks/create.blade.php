<x-app-layout>
    @section('title', 'Add Task to ' . $section->title)

    <div class="container">
        <h1>Add Task to {{ $section->title }}</h1>

        <form action="{{ route('tasks.store', $section->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="text">Task Text</label>
                <textarea name="text" id="text" class="form-control">{{ old('text') }}</textarea>
            </div>
            <button type="submit" class="btn btn-success">Add Task</button>
        </form>
    </div>
</x-app-layout>
