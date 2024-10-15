<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="container">
        <h1>Edytuj Kurs</h1>
        <form method="POST" action="{{ route('course.update', $course) }}">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <label for="title" class="form-label">Tytuł</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $course->title }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Opis</label>
                <textarea class="form-control" id="description" name="description" required>{{ $course->description }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary">Zaktualizuj Kurs</button>
        </form>
    </div>
</x-app-layout>
