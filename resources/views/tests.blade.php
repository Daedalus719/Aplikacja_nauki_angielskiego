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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>{{ $course->title }}</span>
                        <div>
                            <a href="{{ route('tests.edit', $test) }}" class="btn btn-sm btn-outline-secondary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-pencil-fill" viewBox="0 0 16 16">
                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.5.5 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11z"/>
                                  </svg>
                            </a>
                            <form action="{{ route('tests.destroy', $test) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                        <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                                      </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $test->title }}</h5>
                        <p class="card-text">{{ $test->description }}</p>
                        <a href="{{ route('test.show', $test) }}" class="btn btn-primary">Przejdź do testu</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>