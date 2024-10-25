<x-app-layout>
    @section('title', 'Strona główna')

    <div class="container mt-5">
        <h2>Szukaj Słów</h2>
        <form id="wordSearchForm">
            <div class="input-group mb-3">
                <input type="text" class="form-control" id="searchWord"
                       placeholder="Wpisz słowo (polskie lub angielskie)" aria-label="Szukaj słowa" autocomplete="off">
            </div>
            <ul id="wordSuggestions" class="list-group" style="position: absolute; z-index: 1000; max-width: 400px;">
                <!-- Search results will be dynamically added here -->
            </ul>
        </form>
    </div>


    <div class="modal fade" id="wordModal" tabindex="-1" aria-labelledby="wordModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">

                </div>
                <div class="modal-footer" id="modalFooter">

                </div>
            </div>
        </div>
    </div>

    @vite('resources/js/dashboard.js')
</x-app-layout>
