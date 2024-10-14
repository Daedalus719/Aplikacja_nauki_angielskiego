<x-app-layout>
    @section('title', 'Dodaj Nową Regułę')

    <div class="container mt-4">
        <h2 class="text-center mb-4">Dodaj Nową Regułę</h2>

        <form id="addRuleForm">
            @csrf
            <div class="mb-3">
                <label for="section" class="form-label">Wybierz Sekcję</label>
                <select class="form-select" id="section" name="section_id" required>
                    <option value="">-- Wybierz Sekcję --</option>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}">{{ $section->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="ruleText" class="form-label">Reguła</label>
                <textarea class="form-control" id="ruleText" name="rule_text" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Dodaj Regułę</button>
        </form>

    </div>
</x-app-layout>
