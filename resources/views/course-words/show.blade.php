<x-app-layout>
    @section('title', 'Kurs dla: ' . $course->title)

    <div class="container">

        <a href="{{ route('course.index') }}" class="btn btn-secondary mb-3 mt-3">Przejdź do wyboru kursów</a>

        @if (Auth::check() && Auth::user()->usertype === 'Admin')
            <h2>Dodaj słowo do Kursu: {{ $course->title }}</h2>

            <form method="POST" action="{{ route('course-words.store', $course) }}">
                @csrf
                <div class="mb-3">
                    <label for="english_word" class="form-label">Zacznij wpisywać i wybierz angielskie słowo z listy</label>
                    <input type="text" class="form-control" id="english_word" name="english_word" autocomplete="off">
                    <input type="hidden" id="word_id" name="word_id">
                    <div id="suggestions" class="list-group" style="display: none;"></div>
                </div>

                <button type="submit" class="btn btn-success mb-2">Dodaj słowo</button>
            </form>
        @endif

        <h2>Słownictwo dla tego kursu:</h2>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Wymowa</th>
                    <th scope="col">Angielskie Słowo</th>
                    <th scope="col">Polskie Tłumaczenie</th>
                    <th scope="col">Typ Słowa</th>
                    @if (Auth::check() && Auth::user()->usertype === 'Admin')
                        <th scope="col">Akcje</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($courseWords as $word)
                    <tr>
                        <td>
                            <button class="btn btn-outline-primary btn-sm" onclick="speakWord('{{ $word->english_word }}')">🔊</button>
                        </td>
                        <td>{{ $word->english_word }}</td>
                        <td>{{ $word->polish_word }}</td>
                        <td>{{ $word->word_type }}</td>
                        @if (Auth::check() && Auth::user()->usertype === 'Admin')
                            <td>
                                <button class="btn btn-sm btn-outline-danger"
                                        onclick="confirmDeleteWord('{{ route('course-words.destroy', [$course->id, $word->id]) }}')">
                                    Usuń
                                </button>
                            </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Potwierdź usunięcie</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Czy na pewno chcesz usunąć to słowo?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                    <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Usuń</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let deleteUrl = '';

        function confirmDeleteWord(url) {
            deleteUrl = url;
            var myModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            myModal.show();
        }

        document.getElementById('confirmDeleteBtn').addEventListener('click', function () {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = deleteUrl;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';

            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        });

        const suggestionsContainer = document.getElementById('suggestions');

        document.getElementById('english_word').addEventListener('input', function() {
            let query = this.value;

            if (query.length >= 2) {
                fetch('{{ route('course-words.search') }}?query=' + query)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsContainer.innerHTML = '';
                        suggestionsContainer.style.display = 'block';

                        data.forEach(word => {
                            let suggestion = document.createElement('a');
                            suggestion.classList.add('list-group-item', 'list-group-item-action');
                            suggestion.innerText = word.english_word + ' (' + word.polish_word + ')';
                            suggestion.addEventListener('click', function() {
                                document.getElementById('english_word').value = word.english_word;
                                document.getElementById('word_id').value = word.id;
                                suggestionsContainer.innerHTML = '';
                                suggestionsContainer.style.display = 'none';
                                speakWord(word.english_word);
                            });
                            suggestionsContainer.appendChild(suggestion);
                        });
                    });
            } else {
                suggestionsContainer.innerHTML = '';
                suggestionsContainer.style.display = 'none';
            }
        });

        document.addEventListener('click', function(event) {
            if (!suggestionsContainer.contains(event.target) && event.target.id !== 'english_word') {
                suggestionsContainer.innerHTML = '';
                suggestionsContainer.style.display = 'none';
            }
        });

        function speakWord(word) {
            const sanitizedWord = word.replace(/[\/\\$$]/g, ' ');

            const utterance = new SpeechSynthesisUtterance(sanitizedWord.trim());
            utterance.lang = 'en-US';
            speechSynthesis.speak(utterance);
        }
    </script>
</x-app-layout>
