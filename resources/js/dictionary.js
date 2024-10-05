document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchWord');
    const suggestionsList = document.getElementById('wordSuggestions');
    const wordTableBody = document.getElementById('wordTableBody');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let offset = 60;
    let isLoading = false;

    // --- Search Input Logic ---
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

    // --- Function to load more words (for infinite scrolling) ---
    function loadMoreWords() {
        if (isLoading) return;
        isLoading = true;

        const loader = document.createElement('tr');
        loader.innerHTML = '<td colspan="5" class="text-center">Loading...</td>';
        wordTableBody.appendChild(loader);

        fetch(`/words/load-more?offset=${offset}`)
            .then(response => response.json())
            .then(data => {
                loader.remove();
                data.forEach(word => {
                    const row = createWordRow(word);
                    wordTableBody.appendChild(row);
                });
                offset += 60;
                isLoading = false;
            });
    }

    // --- Function to create a word row dynamically ---
    function createWordRow(word) {
        const row = document.createElement('tr');
        row.id = `word-row-${word.id}`;
        row.innerHTML = `
            <td class="english-word">${word.english_word}</td>
            <td class="pronunciation">${word.pronunciation !== null ? word.pronunciation : ''}</td>
            <td class="word-type">${capitalize(word.word_type)}</td>
            <td class="polish-word">${word.polish_word}</td>
            <td>${renderActionButtons(word.id)}</td>`;
        return row;
    }

    // --- Function to render action buttons based on user type ---
    function renderActionButtons(wordId) {
        if (userType === 'Admin') {
            return `
                <button class="btn btn-sm btn-outline-secondary edit-btn" data-word-id="${wordId}">Edytuj</button>
                <button class="btn btn-sm btn-outline-success save-btn" data-word-id="${wordId}" style="display: none;">Zapisz</button>
                <form action="/words/${wordId}" method="POST" style="display:inline;">
                    <input type="hidden" name="_token" value="${csrfToken}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Czy na pewno chcesz usunąć to słowo?')">Usuń</button>
                </form>`;
        } else {
            return '';  // No buttons for non-admin users
        }
    }

    // --- Event delegation to handle Edit and Save button clicks for all rows (static + dynamic) ---
    wordTableBody.addEventListener('click', function (e) {
        if (e.target.classList.contains('edit-btn')) {
            const wordId = e.target.getAttribute('data-word-id');
            const row = document.getElementById(`word-row-${wordId}`);
            toggleEditMode(row, true);
        }

        if (e.target.classList.contains('save-btn')) {
            const wordId = e.target.getAttribute('data-word-id');
            const row = document.getElementById(`word-row-${wordId}`);
            saveWordChanges(row, wordId);
        }
    });

    // --- Function to toggle edit mode for a word row ---
    function toggleEditMode(row, isEditing) {
        const wordId = row.id.split('-').pop();
        const englishWord = row.querySelector('.english-word').textContent.trim();
        const pronunciation = row.querySelector('.pronunciation').textContent.trim();
        const wordType = row.querySelector('.word-type').textContent.toLowerCase();
        const polishWord = row.querySelector('.polish-word').textContent.trim();

        if (isEditing) {
            // Switch to edit mode
            row.classList.add('editable-row');
            row.querySelector('.english-word').innerHTML = `<input type="text" class="form-control" value="${englishWord}" id="english-word-${wordId}">`;
            row.querySelector('.pronunciation').innerHTML = `<input type="text" class="form-control" value="${pronunciation}" id="pronunciation-${wordId}">`;
            row.querySelector('.word-type').innerHTML = `
            <select class="form-control" id="word-type-${wordId}">
                <option value="Noun (Rzeczownik)" ${wordType === 'noun' ? 'selected' : ''}>Rzeczownik</option>
                <option value="Verb (Czasownik)" ${wordType === 'verb' ? 'selected' : ''}>Czasownik</option>
                <option value="Adjective (Przymiotnik)" ${wordType === 'adjective' ? 'selected' : ''}>Przymiotnik</option>
                <option value="Adverb (Przysłowek)" ${wordType === 'adverb' ? 'selected' : ''}>Przysłowek</option>
                <option value="Pronoun (Zaimek)" ${wordType === 'pronoun' ? 'selected' : ''}>Zaimek</option>
                <option value="Proverb (Przysłowie)" ${wordType === 'proverb' ? 'selected' : ''}>Przysłowie</option>
                <option value="Preposition (Przyimek)" ${wordType === 'preposition' ? 'selected' : ''}>Przyimek</option>
                <option value="Conjunction (Spójnik)" ${wordType === 'conjunction' ? 'selected' : ''}>Spójnik</option>
                <option value="Interjection (Wykrzyknik)" ${wordType === 'interjection' ? 'selected' : ''}>Wykrzyknik</option>
                <option value="Idiom (Idiom)" ${wordType === 'idiom' ? 'selected' : ''}>Idiom</option>
            </select>`;
            row.querySelector('.polish-word').innerHTML = `<input type="text" class="form-control" value="${polishWord}" id="polish-word-${wordId}">`;

            row.querySelector('.edit-btn').style.display = 'none';
            row.querySelector('.save-btn').style.display = 'inline';
        } else {
            // Switch back to non-edit mode (static row)
            const newEnglishWord = document.getElementById(`english-word-${wordId}`).value;
            const newPronunciation = document.getElementById(`pronunciation-${wordId}`).value;
            const newWordType = document.getElementById(`word-type-${wordId}`).value;
            const newPolishWord = document.getElementById(`polish-word-${wordId}`).value;

            // Directly update the textContent for each field with new values
            row.querySelector('.english-word').textContent = newEnglishWord;
            row.querySelector('.pronunciation').textContent = newPronunciation;
            row.querySelector('.word-type').textContent = capitalize(newWordType);
            row.querySelector('.polish-word').textContent = newPolishWord;

            // Reset row from editable to non-editable
            row.classList.remove('editable-row');
            row.querySelector('.edit-btn').style.display = 'inline';
            row.querySelector('.save-btn').style.display = 'none';
        }
    }

    // --- Function to save word changes ---
    function saveWordChanges(row, wordId) {
        const englishWord = document.getElementById(`english-word-${wordId}`).value;
        const pronunciation = document.getElementById(`pronunciation-${wordId}`).value;
        const wordType = document.getElementById(`word-type-${wordId}`).value;
        const polishWord = document.getElementById(`polish-word-${wordId}`).value;

        const data = {
            english_word: englishWord,
            pronunciation: pronunciation,
            word_type: wordType,
            polish_word: polishWord,
            _token: csrfToken,
        };

        fetch(`/words/${wordId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify(data),
        })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    // Toggle back to non-edit mode after saving
                    toggleEditMode(row, false);
                } else {
                    alert('Error saving the word.');
                }
            })
            .catch(error => {
                console.error('Error saving word:', error);
            });
    }



    // --- Scroll event listener ---
    window.addEventListener('scroll', function () {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 50) {
            loadMoreWords();
        }
    });

    // --- Utility function to capitalize the first letter of a string ---
    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    }
});
