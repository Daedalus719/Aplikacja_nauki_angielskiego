<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="container mt-5">
        <h1>Dodaj Nowy Kurs</h1>
        <form method="POST" action="{{ route('course.store') }}">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">Tytuł Kursu</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Opis Kursu</label>
                <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Dodaj Kurs</button>
        </form>
    </div>
</x-app-layout>
