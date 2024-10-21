<x-app-layout>
    @section('title', 'Reguły dla ' . $section->title)

    <div class="container">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <ul class="list-group mb-3" id="rules-list">
            @forelse ($section->rules as $rule)
                <li class="list-group-item" data-id="{{ $rule->id }}">
                    <div class="rule-content">{!! $rule->content !!}</div>

                    <div class="buttons mt-2">
                        <button class="btn btn-sm btn-secondary edit-rule" data-id="{{ $rule->id }}">Edytuj</button>
                        <button class="btn btn-sm btn-danger delete-rule" data-id="{{ $rule->id }}">Usuń</button>
                    </div>

                    <div class="edit-form mt-3" style="display: none;">
                        <textarea class="form-control" name="edit-content">{{ $rule->content }}</textarea>
                        <button class="btn btn-success save-rule mt-2" data-id="{{ $rule->id }}">Zapisz</button>
                        <button class="btn btn-secondary cancel-edit mt-2">Anuluj</button>
                    </div>
                </li>

            @empty
                <p>Brak dodanych reguł!</p>
            @endforelse
        </ul>

        <form action="{{ route('sections.addRule', $section->id) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="content" class="form-label">Dodaj regułę</label>
                <textarea name="content" id="content" class="form-control"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Dodaj</button>
        </form>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#content'))
            .catch(error => {
                console.error(error);
            });

        let editors = {};

        document.querySelectorAll('.edit-rule').forEach(button => {
            button.addEventListener('click', function() {
                const ruleItem = this.closest('li');
                ruleItem.querySelector('.rule-content').style.display = 'none';
                ruleItem.querySelector('.edit-form').style.display = 'block';

                const textarea = ruleItem.querySelector('textarea');
                const ruleId = this.dataset.id;

                if (editors[ruleId]) {
                    editors[ruleId].destroy();
                }

                ClassicEditor.create(textarea)
                    .then(editor => {
                        editors[ruleId] = editor;
                    })
                    .catch(error => console.error(error));
            });
        });

        document.querySelectorAll('.cancel-edit').forEach(button => {
            button.addEventListener('click', function() {
                const ruleItem = this.closest('li');
                const ruleId = this.closest('li').dataset.id;

                if (editors[ruleId]) {
                    editors[ruleId].destroy();
                }

                ruleItem.querySelector('.rule-content').style.display = 'block';
                ruleItem.querySelector('.edit-form').style.display = 'none';
            });
        });


        document.querySelectorAll('.save-rule').forEach(button => {
            button.addEventListener('click', function() {
                const ruleItem = this.closest('li');
                const ruleId = this.dataset.id;
                const content = ruleItem.querySelector('textarea').value;

                fetch(`/rules/${ruleId}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            content
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            ruleItem.querySelector('.rule-content').innerHTML = data.content;
                            ruleItem.querySelector('.rule-content').style.display = 'block';
                            ruleItem.querySelector('.edit-form').style.display = 'none';
                        } else {
                            console.error('Error saving rule:', data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                    });
            });
        });


        document.querySelectorAll('.delete-rule').forEach(button => {
            button.addEventListener('click', function() {
                const ruleId = this.dataset.id;

                if (confirm('Are you sure you want to delete this rule?')) {
                    fetch(`/rules/${ruleId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.closest('li').remove();
                            }
                        });
                }
            });
        });
    </script>

    @vite('resources/css/sections.css')
</x-app-layout>
