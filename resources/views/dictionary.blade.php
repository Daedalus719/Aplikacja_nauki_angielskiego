<!-- resources/views/dictionary.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Słownik') }}
        </h2>
    </x-slot>

    <div class="container mt-5">
        <h3>Wszystkie słowa z ich tłumaczeniami</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Angielskie słowo:</th>
                    <th>Polskie tłumaczenie</th>
                </tr>
            </thead>
            <tbody>
                @foreach($words as $word)
                    <tr>
                        <td>{{ $word->english_word }}</td>
                        <td>{{ $word->polish_word }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
