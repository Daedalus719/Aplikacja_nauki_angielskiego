<x-app-layout>
    @section('title', 'Zadania: Czasowniki Nieregularne')

    <div class="container mt-5">
        <h2 class="main-text mb-2">Wpisz brakujące formy czasowników:</h2>
        <form id="irregularVerbsTaskForm">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Czasownik (I forma)</th>
                        <th>Czasownik (II forma)</th>
                        <th>Czasownik (III forma)</th>
                        <th>Polskie tłumaczenie</th>
                    </tr>
                </thead>
                <tbody id="verbsTableBody">
                    <!-- js inserted -->
                </tbody>
            </table>
            <div id="resultMessage" class="mt-3" style="display:none;"></div>
            <button type="button" class="btn btn-success mt-3" id="checkAnswersBtn">Sprawdź</button>
            <button type="button" class="btn btn-secondary mt-3" id="tryAgainBtn">Spróbuj ponownie dla tych samych wyrażeń</button>
            <button type="button" class="btn btn-info mt-3 ms-2" id="refreshPageBtn">Spróbuj ponownie dla innych wyrażeń</button>
        </form>
    </div>


    <script>
        window.verbData = @json($verbs);

        document.getElementById('refreshPageBtn').addEventListener('click', function () {
            location.reload();
        });
    </script>


    @vite('resources/js/verb-task.js')
</x-app-layout>
