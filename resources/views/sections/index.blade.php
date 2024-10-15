<x-app-layout>
    <div class="container">
        <h1>Sekcje</h1>

        @if (Auth::check() && Auth::user()->usertype === 'Admin')
            <a href="{{ route('sections.create') }}" class="btn btn-primary mb-3">Stwórz nową sekcję</a>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($sections->count())
            <ul class="list-group">
                @foreach ($sections as $section)
                    <li class="list-group-item">
                        <strong>{{ $section->title }}</strong>
                        <div class="mt-2">
                            <a href="{{ route('sections.show', $section->id) }}" class="btn btn-sm btn-info">Przejrzyj
                                reguły</a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p>Brak dostępnych sekcji!</p>
        @endif
    </div>
</x-app-layout>
