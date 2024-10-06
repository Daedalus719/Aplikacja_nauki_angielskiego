<x-app-layout>
    @section('title', 'Kurs dla: {{ $course->title }}')

    <div class="container">
        @if (Auth::check() && Auth::user()->usertype === 'Admin')
            <h2>Add Word to Course: {{ $course->title }}</h2>

            <form method="POST" action="{{ route('course-words.store', $course) }}">
                @csrf
                <div class="mb-3">
                    <label for="english_word" class="form-label">English Word</label>
                    <input type="text" class="form-control" id="english_word" name="english_word" autocomplete="off">
                    <input type="hidden" id="word_id" name="word_id">
                    <div id="suggestions" class="list-group"></div>
                </div>

                <button type="submit" class="btn btn-primary">Add Word</button>
            </form>
        @endif

        <h2>Course Words</h2>
        <ul class="list-group">
            @foreach ($courseWords as $word)
                <li class="list-group-item">
                    {{ $word->english_word }} - {{ $word->polish_word }}
                    <small class="text-muted">({{ $word->word_type }}, {{ $word->pronunciation ?? '' }})</small>
                </li>
            @endforeach
        </ul>
    </div>

    <script>
        document.getElementById('english_word').addEventListener('input', function() {
            let query = this.value;

            if (query.length >= 2) {
                fetch('{{ route('course-words.search') }}?query=' + query)
                    .then(response => response.json())
                    .then(data => {
                        let suggestions = document.getElementById('suggestions');
                        suggestions.innerHTML = '';

                        data.forEach(word => {
                            let suggestion = document.createElement('a');
                            suggestion.classList.add('list-group-item', 'list-group-item-action');
                            suggestion.innerText = word.english_word + ' (' + word.polish_word + ')';
                            suggestion.addEventListener('click', function() {
                                document.getElementById('english_word').value = word
                                    .english_word;
                                document.getElementById('word_id').value = word.id;
                                suggestions.innerHTML = '';
                            });
                            suggestions.appendChild(suggestion);
                        });
                    });
            } else {
                document.getElementById('suggestions').innerHTML = '';
            }
        });
    </script>
</x-app-layout>
