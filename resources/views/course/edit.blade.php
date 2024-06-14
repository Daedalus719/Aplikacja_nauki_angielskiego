<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="container">
        <h1>Edit Test</h1>
        <form method="POST" action="{{ route('course.update', $course) }}">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $course->title }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" required>{{ $course->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Update Course</button>
        </form>
    </div>
</x-app-layout>
