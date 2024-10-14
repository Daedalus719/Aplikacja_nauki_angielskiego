<x-app-layout>
    @section('title', 'Reguły tworzenia zdań')

    <div class="container mt-4">
        <div class="text-center mb-4">
            <button class="btn btn-secondary me-2" id="addSectionBtn">Dodaj nową sekcję</button>
            <a href="{{ route('sentence-rules.addPage') }}" class="btn btn-primary">Dodaj nową regułę</a>
        </div>

        <div id="sectionButtons" class="d-flex flex-wrap justify-content-center">
            @foreach ($sections as $section)
                <button class="btn btn-primary section-btn" data-section-id="{{ $section->id }}">
                    {{ $section->title }}
                </button>
            @endforeach
        </div>

        <div id="sectionContent" style="display: none;">
            <h3 id="sectionTitle"></h3>
            <div id="rulesContainer"></div>
            <button class="btn btn-secondary mt-4" id="returnBtn">Wróć do listy</button>
        </div>

        <div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addSectionModalLabel">Dodaj nową sekcję</h5>
                        <button type="button" class="btn-close" id="closeBtn" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" id="newSectionTitle" class="form-control" placeholder="Nazwa nowej sekcji">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="cancelBtn">Anuluj</button>
                        <button type="button" class="btn btn-primary" id="saveSectionBtn">Dodaj sekcję</button>
                    </div>
                </div>
            </div>
        </div>


    </div>

    @vite('resources/js/sentence-rules.js')
</x-app-layout>
