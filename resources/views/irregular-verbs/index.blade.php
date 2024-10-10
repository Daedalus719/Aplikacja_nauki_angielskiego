<x-app-layout>
    @section('title', 'Czasowniki Nieregularne')

    <div class="container mt-5">

        @if (Auth::check() && Auth::user()->usertype === 'Admin')
            <h3 class="mb-2">Dodaj nowy wpis</h3>
            <form action="{{ route('irregular-verbs.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="verb_1st_form" class="form-label">I forma bezokolicznik (infinitive)</label>
                    <input type="text" class="form-control" id="verb_1st_form" name="verb_1st_form" required>
                </div>
                <div class="mb-3">
                    <label for="verb_2nd_form" class="form-label">II forma (past tense)</label>
                    <input type="text" class="form-control" id="verb_2nd_form" name="verb_2nd_form" required>
                </div>
                <div class="mb-3">
                    <label for="verb_3rd_form" class="form-label">III forma (past participle)</label>
                    <input type="text" class="form-control" id="verb_3rd_form" name="verb_3rd_form" required>
                </div>
                <div class="mb-3">
                    <label for="polish_translation" class="form-label">Tłumaczenie</label>
                    <input type="text" class="form-control" id="polish_translation" name="polish_translation"
                        required>
                </div>
                <button type="submit" class="btn btn-primary">Add Verb</button>
            </form>
        @endif

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>I forma bezokolicznik (infinitive)</th>
                    <th>II forma (past tense)</th>
                    <th>III forma (past participle)</th>
                    <th>Tłumaczenie</th>
                    @if (Auth::check() && Auth::user()->usertype === 'Admin')
                        <th>Akcje</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($irregularVerbs as $verb)
                    <tr>
                        <td>{{ $verb->verb_1st_form }}</td>
                        <td>{{ $verb->verb_2nd_form }}</td>
                        <td>{{ $verb->verb_3rd_form }}</td>
                        <td>{{ $verb->polish_translation }}</td>
                        @if (Auth::check() && Auth::user()->usertype === 'Admin')
                            <td>
                                <button class="btn btn-sm btn-warning edit-btn">Edytuj</button>
                                <button class="btn btn-sm btn-danger delete-btn">Usuń</button>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @vite('resources/js/irregular-verbs.js')
</x-app-layout>
