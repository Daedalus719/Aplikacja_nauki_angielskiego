<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="jumbotron">
        <h1 class="display-4">Witaj w Nauce Angielskiego</h1>
        <p class="lead">Rozwijaj swoje umiejętności języka angielskiego dzięki naszej szerokiej gamie kursów i zasobów.</p>
        <hr class="my-4">
        <p>Zacznij naukę już teraz i śledź swój postęp w miarę nauki.</p>
    </div>

    <div class="row">
        @foreach($courses as $course)
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        {{ $course->title }}
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $course->title }}</h5>
                        <p class="card-text">{{ $course->description }}</p>
                        <a href="{{ route('course.show', $course) }}" class="btn btn-primary">Rozpocznij Kurs</a>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Add new course card -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    Dodaj Nowy Kurs
                </div>
                <div class="card-body text-center">
                    <h5 class="card-title">Stwórz nowy kurs</h5>
                    <p class="card-text">Dodaj nowy kurs do swojej oferty.</p>
                    <a href="{{ route('create') }}" class="btn btn-primary">Dodaj Kurs</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
