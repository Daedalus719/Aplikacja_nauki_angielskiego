<x-app-layout>
    <div class="container">
        <h1>Stwórz sekcję</h1>

        <form action="{{ route('sections.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Tytuł</label>
                <input type="text" name="title" class="form-control" id="title" required>
            </div>

            <button type="submit" class="btn btn-success">Zapisz</button>
        </form>
    </div>
</x-app-layout>
