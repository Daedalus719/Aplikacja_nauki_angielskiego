<x-app-layout>
    @section('title', 'Zadania: Czasowniki Nieregularne')

    <div class="container mt-5">
        <h3>Wpisz brakujące formy czasowników:</h3>
        <form id="irregularVerbsTaskForm">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Verb (1st form)</th>
                        <th>Verb (2nd form)</th>
                        <th>Verb (3rd form)</th>
                        <th>Polish Translation</th>
                    </tr>
                </thead>
                <tbody id="verbsTableBody">
                    <!-- JavaScript will dynamically generate rows here -->
                </tbody>
            </table>
            <button type="button" class="btn btn-primary mt-3" id="checkAnswersBtn">Check</button>
            <button type="button" class="btn btn-secondary mt-3" id="tryAgainBtn">Try Again</button>
        </form>
    </div>

    <pre>{{ json_encode($verbs, JSON_PRETTY_PRINT) }}</pre>



    <script>
        window.verbData = @json($verbs);
    </script>


    @vite('resources/js/verb-task.js')
</x-app-layout>
