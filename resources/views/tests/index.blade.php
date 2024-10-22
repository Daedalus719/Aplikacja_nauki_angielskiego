<x-app-layout>
    @section('title', 'Testy słownikowo - gramatyczne')

    <div class="container mt-5">
        <div class="row">
            @if ($courses->isEmpty())
                <div class="col-md-12 text-center">
                    <h2 class="text-muted">UPS... Ta podstrona jest w trakcie prac. Przepraszamy</h2>
                </div>
            @else
                @foreach ($courses as $course)
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Testy dla {{ $course->title }}</span>
                            </div>
                            <div class="card-body">
                                <p class="card-text">{{ $course->description }}</p>
                                <a href="{{ route('tests.show', $course->id) }}" class="btn btn-primary">
                                    Przejdź do testów
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</x-app-layout>
