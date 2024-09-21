document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchWord');
    const suggestionsList = document.getElementById('wordSuggestions');

    searchInput.addEventListener('input', function () {
        const query = this.value;

        if (query.length > 1) {
            fetch(`/search-words?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    suggestionsList.innerHTML = '';

                    if (data.length) {
                        data.forEach(word => {
                            const listItem = document.createElement('li');
                            listItem.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
                            listItem.innerHTML = `
                                <span>${word.english_word} (${word.polish_word})</span>
                                <span>
                                    ${word.language === 'english' ? '🇬🇧' : '🇵🇱'}
                                </span>
                            `;
                            listItem.addEventListener('click', () => {
                                window.location.href = `/words/${word.id}`;
                            });
                            suggestionsList.appendChild(listItem);
                        });
                    } else {
                        const noResult = document.createElement('li');
                        noResult.classList.add('list-group-item');
                        noResult.textContent = 'No results found';
                        suggestionsList.appendChild(noResult);
                    }
                });
        } else {
            suggestionsList.innerHTML = '';
        }
    });

    document.addEventListener('click', function (event) {
        if (!suggestionsList.contains(event.target) && event.target !== searchInput) {
            suggestionsList.innerHTML = '';
        }
    });
});

//edytowanie i usuwanie wpisów przez ajax
document.addEventListener('DOMContentLoaded', function() {
    const editButtons = document.querySelectorAll('.edit-btn');
    const saveButtons = document.querySelectorAll('.save-btn');

    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const wordId = this.getAttribute('data-word-id');
            const row = document.getElementById(`word-row-${wordId}`);

            const englishWord = row.querySelector('.english-word').textContent;
            const pronunciation = row.querySelector('.pronunciation').textContent;
            const wordType = row.querySelector('.word-type').textContent.toLowerCase();
            const polishWord = row.querySelector('.polish-word').textContent;

            row.querySelector('.english-word').innerHTML = `<input type="text" class="form-control" value="${englishWord}" id="english-word-${wordId}">`;
            row.querySelector('.pronunciation').innerHTML = `<input type="text" class="form-control" value="${pronunciation}" id="pronunciation-${wordId}">`;
            row.querySelector('.word-type').innerHTML = `
                <select class="form-control" id="word-type-${wordId}">
                    <option value="Noun (Rzeczownik)" ${wordType === 'noun' ? 'selected' : ''}>Rzeczownik</option>
                    <option value="Verb (Czasownik)" ${wordType === 'verb' ? 'selected' : ''}>Czasownik</option>
                    <option value="Adjective (Przymiotnik)" ${wordType === 'adjective' ? 'selected' : ''}>Przymiotnik</option>
                    <option value="Adverb (Przysłowek)" ${wordType === 'adverb' ? 'selected' : ''}>Przysłowek</option>
                    <option value="Pronoun (Zaimek)" ${wordType === 'pronoun' ? 'selected' : ''}>Zaimek</option>
                    <option value="Preposition (Przyimek)" ${wordType === 'preposition' ? 'selected' : ''}>Przyimek</option>
                    <option value="Conjunction (Spójnik)" ${wordType === 'conjunction' ? 'selected' : ''}>Spójnik</option>
                    <option value="Interjection (Wykrzyknik)" ${wordType === 'interjection' ? 'selected' : ''}>Wykrzyknik</option>
                    <option value="Idiom (Idiom)" ${wordType === 'idiom' ? 'selected' : ''}>Idiom</option>
                </select>`;
            row.querySelector('.polish-word').innerHTML = `<input type="text" class="form-control" value="${polishWord}" id="polish-word-${wordId}">`;

            row.querySelector(`.save-btn[data-word-id="${wordId}"]`).style.display = 'inline';
            this.style.display = 'none';
        });
    });

    saveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const wordId = this.getAttribute('data-word-id');
            const row = document.getElementById(`word-row-${wordId}`);

            const englishWord = document.getElementById(`english-word-${wordId}`).value;
            const pronunciation = document.getElementById(`pronunciation-${wordId}`).value;
            const wordType = document.getElementById(`word-type-${wordId}`).value;
            const polishWord = document.getElementById(`polish-word-${wordId}`).value;

            fetch(`/words/${wordId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    english_word: englishWord,
                    pronunciation: pronunciation,
                    word_type: wordType,
                    polish_word: polishWord
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {

                    row.querySelector('.english-word').textContent = englishWord;
                    row.querySelector('.pronunciation').textContent = pronunciation;
                    row.querySelector('.word-type').textContent = wordType.charAt(0).toUpperCase() + wordType.slice(1);
                    row.querySelector('.polish-word').textContent = polishWord;

                    row.querySelector(`.edit-btn[data-word-id="${wordId}"]`).style.display = 'inline';
                    row.querySelector(`.save-btn[data-word-id="${wordId}"]`).style.display = 'none';
                }
            });
        });
    });
});
