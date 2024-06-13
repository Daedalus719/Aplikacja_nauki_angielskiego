<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="container">
        <h1>Testy dla kursu: {{ $test->title }}</h1>
        <ul class="nav nav-tabs">
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('tests.vowel', $test) }}">Test z brakującymi samogłoskami</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('tests.translation', $test) }}">Test tłumaczenia</a>
            </li>
        </ul>
    </div>
</x-app-layout>
