<x-app-layout>
    @section('title', 'Układanka słów w zdanie dla ' . $section->title)

    <div class="container">
        <h1>Układanka dla czasu "{{ $section->title }}"</h1>

        @if (Auth::check() && Auth::user()->usertype === 'Admin')
            <h3>Dodaj zdanie:</h3>
            <form action="{{ route('sentence_game.add-sentence', $section->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="sentence">Sentence</label>
                    <input type="text" name="sentence" id="sentence" class="form-control"
                        placeholder="Wpisz treść..." required>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Dodaj zdanie</button>
                <a href="{{ route('sentence_game.all-sentences', $section->id) }}"
                    class="btn btn-secondary mt-3 ml-2">Przejrzyj wszystkie zdania</a>
            </form>
        @endif

        @if ($sentence)
            <p>Pokładaj słowa aby ułuzyć z nim poprawne zdanie:</p>

            <div class="sentence-words" data-correct-sentence="{{ $sentence->sentence }}">
                @php
                    $words = explode(' ', $sentence->sentence);
                    shuffle($words);
                @endphp

                @foreach ($words as $word)
                    <span class="word draggable" draggable="true">{{ $word }}</span>
                @endforeach
            </div>

            <div class="drop-zones mt-3">
                <p>Upuść słowa w poniższych polach w odpowiedniej kolejności::</p>
                @for ($i = 0; $i < count($words); $i++)
                    <div class="drop-field" data-index="{{ $i }}" ondrop="dropWord(event)"
                        ondragover="allowDrop(event)">
                        <!-- drop zones -->
                    </div>
                @endfor
            </div>

            <button class="btn btn-success mt-4" onclick="location.reload()">Wygeneruj nowe zdanie.</button>
        @else
            <p>Brak znalezionych zdań. NIe badź zły. To znaczy że moderatorzy aktualnie pracują nad tą sekcją.</p>
        @endif
    </div>

    <script>
        const words = document.querySelectorAll('.draggable');
        const dropFields = document.querySelectorAll('.drop-field');

        words.forEach(word => {
            word.addEventListener('dragstart', dragStart);
        });

        function dragStart(e) {
            e.dataTransfer.setData('text/plain', e.target.innerText);
            e.dataTransfer.effectAllowed = 'move';
        }

        function allowDrop(e) {
            e.preventDefault();
        }

        function dropWord(e) {
            e.preventDefault();
            const droppedWord = e.dataTransfer.getData('text/plain');

            if (e.target.innerText !== '') {
                e.target.innerText = '';
            }
            e.target.innerText = droppedWord;

            checkSentenceCorrectness();
        }

        function checkSentenceCorrectness() {
            const correctSentence = document.querySelector('.sentence-words').dataset.correctSentence.split(' ');
            let isCorrect = true;

            dropFields.forEach((field, index) => {
                const droppedWord = field.innerText.trim();
                if (droppedWord === correctSentence[index]) {
                    field.classList.add('correct');
                    field.classList.remove('incorrect');
                } else {
                    field.classList.add('incorrect');
                    field.classList.remove('correct');
                    isCorrect = false;
                }
            });
        }
    </script>

    @vite('resources/css/sentence-play.css')
</x-app-layout>
