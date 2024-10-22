document.addEventListener('DOMContentLoaded', function () {
    const wordTableBody = document.getElementById('wordTableBody');
    let currentOffset = 0;
    const limit = 60;
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const userTypeMeta = document.querySelector('meta[name="usertype"]');
    const userType = userTypeMeta ? userTypeMeta.content : '';

    loadWords(currentOffset, limit);

    document.querySelector('.flex-grow-1').addEventListener('scroll', function () {
        if (this.scrollTop + this.clientHeight >= this.scrollHeight) {
            loadWords(currentOffset, limit);
        }
    });

    function loadWords(offset, limit) {
        fetch(`/words/load-more?offset=${offset}`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    currentOffset += limit;
                    data.sort((a, b) => a.english_word.localeCompare(b.english_word));
                    data.forEach(word => appendWordRow(word));
                }
            });
    }

    function appendWordRow(word) {
        const row = createWordRow(word);
        wordTableBody.appendChild(row);

        const editBtn = row.querySelector('.edit-btn');
        const deleteBtn = row.querySelector('.delete-btn');
        const ttsBtn = row.querySelector('.tts-btn');

        if (editBtn) {
            editBtn.addEventListener('click', () => editWord(word.id));
        }

        if (deleteBtn) {
            deleteBtn.addEventListener('click', () => deleteWord(word.id));
        }

        if (ttsBtn) {
            ttsBtn.addEventListener('click', () => speakWord(word.english_word));
        }
    }

    function createWordRow(word) {
        const row = document.createElement('tr');
        row.id = `word-row-${word.id}`;
        row.innerHTML = `
            <td>${renderActionButtons(word.id)}</td>
            <td class="english-word">${word.english_word}</td>
            <td class="word-type">${(word.word_type)}</td>
            <td class="polish-word">${word.polish_word}</td>`;
        return row;
    }

    function renderActionButtons(wordId) {
        const ttsButton = `<button class="btn btn-sm btn-outline-primary tts-btn" data-english-word="${wordId}">🔊</button>`;

        if (userType === 'Admin' || userType === 'Moderator') {
            return `
                ${ttsButton}
                <button class="btn btn-sm btn-outline-secondary edit-btn" data-word-id="${wordId}">Edytuj</button>
                <button class="btn btn-sm btn-outline-success save-btn" data-word-id="${wordId}" style="display: none;">Zapisz</button>
                <button class="btn btn-sm btn-outline-secondary cancel-btn" data-word-id="${wordId}" style="display: none;">Anuluj</button>
                <button class="btn btn-sm btn-outline-danger delete-btn" data-word-id="${wordId}">Usuń</button>`;
        } else {
            return `${ttsButton}`;
        }
    }

    function editWord(wordId) {
        const row = document.getElementById(`word-row-${wordId}`);
        const saveBtn = row.querySelector('.save-btn');
        const editBtn = row.querySelector('.edit-btn');
        const cancelBtn = row.querySelector('.cancel-btn');

        const englishWord = row.querySelector('.english-word');
        const polishWord = row.querySelector('.polish-word');
        const originalEnglishWord = englishWord.textContent.trim();
        const originalPolishWord = polishWord.textContent.trim();

        englishWord.setAttribute('contenteditable', true);
        polishWord.setAttribute('contenteditable', true);

        englishWord.style.border = '3px solid #007BFF';
        polishWord.style.border = '3px solid #007BFF';

        const wordTypeCell = row.querySelector('.word-type');
        const currentWordType = wordTypeCell.textContent.trim();
        wordTypeCell.innerHTML = `
            <select class="form-select">
                <option value="Noun (Rzeczownik)" ${currentWordType === 'Noun (Rzeczownik)' ? 'selected' : ''}>Rzeczownik</option>
                <option value="Verb (Czasownik)" ${currentWordType === 'Verb (Czasownik)' ? 'selected' : ''}>Czasownik</option>
                <option value="Adjective (Przymiotnik)" ${currentWordType === 'Adjective (Przymiotnik)' ? 'selected' : ''}>Przymiotnik</option>
                <option value="Adverb (Przysłówek)" ${currentWordType === 'Adverb (Przysłówek)' ? 'selected' : ''}>Przysłówek</option>
                <option value="Pronoun (Zaimek)" ${currentWordType === 'Pronoun (Zaimek)' ? 'selected' : ''}>Zaimek</option>
                <option value="Proverb (Przysłowie)" ${currentWordType === 'Proverb (Przysłowie)' ? 'selected' : ''}>Przysłowie</option>
                <option value="Preposition (Przyimek)" ${currentWordType === 'Preposition (Przyimek)' ? 'selected' : ''}>Przyimek</option>
                <option value="Conjunction (Spójnik)" ${currentWordType === 'Conjunction (Spójnik)' ? 'selected' : ''}>Spójnik</option>
                <option value="Interjection (Wykrzyknik)" ${currentWordType === 'Interjection (Wykrzyknik)' ? 'selected' : ''}>Wykrzyknik</option>
                <option value="Idiom (Idiom)" ${currentWordType === 'Idiom (Idiom)' ? 'selected' : ''}>Idiom</option>
            </select>
        `;

        editBtn.style.display = 'none';
        saveBtn.style.display = 'inline-block';
        cancelBtn.style.display = 'inline-block';

        saveBtn.addEventListener('click', () => saveWord(wordId));
        cancelBtn.addEventListener('click', () => cancelEdit(wordId, originalEnglishWord, originalPolishWord, currentWordType));
    }

    function cancelEdit(wordId, originalEnglishWord, originalPolishWord, originalWordType) {
        const row = document.getElementById(`word-row-${wordId}`);
        const saveBtn = row.querySelector('.save-btn');
        const cancelBtn = row.querySelector('.cancel-btn');
        const editBtn = row.querySelector('.edit-btn');

        const englishWord = row.querySelector('.english-word');
        const polishWord = row.querySelector('.polish-word');

        englishWord.textContent = originalEnglishWord;
        polishWord.textContent = originalPolishWord;

        englishWord.removeAttribute('contenteditable');
        polishWord.removeAttribute('contenteditable');

        englishWord.style.border = 'none';
        polishWord.style.border = 'none';

        const wordTypeCell = row.querySelector('.word-type');
        wordTypeCell.innerHTML = originalWordType;

        saveBtn.style.display = 'none';
        cancelBtn.style.display = 'none';
        editBtn.style.display = 'inline-block';
    }


    function saveWord(wordId) {
        const row = document.getElementById(`word-row-${wordId}`);
        const englishWord = row.querySelector('.english-word');
        const polishWord = row.querySelector('.polish-word');
        const saveBtn = row.querySelector('.save-btn');
        const cancelBtn = row.querySelector('.cancel-btn');
        const editBtn = row.querySelector('.edit-btn');

        const data = {
            english_word: englishWord.textContent.trim(),
            polish_word: polishWord.textContent.trim(),
            word_type: row.querySelector('.word-type select').value,
        };

        fetch(`/words/${wordId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    row.querySelectorAll('td').forEach(td => td.removeAttribute('contenteditable'));

                    englishWord.style.border = 'none';
                    polishWord.style.border = 'none';

                    const wordTypeCell = row.querySelector('.word-type');
                    wordTypeCell.textContent = wordTypeCell.querySelector('select').value;

                    saveBtn.style.display = 'none';
                    cancelBtn.style.display = 'none';
                    editBtn.style.display = 'inline-block';
                }
            });
    }

    function deleteWord(wordId) {
        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        modal.show();

        document.getElementById('confirmDeleteBtn').addEventListener('click', () => {
            fetch(`/words/${wordId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`word-row-${wordId}`).remove();
                    }
                    modal.hide();
                });
        }, { once: true });
    }

    function speakWord(word) {
        const sanitizedWord = word.replace(/[\/\\$$]/g, ' ');

        const utterance = new SpeechSynthesisUtterance(sanitizedWord.trim());
        utterance.lang = 'en-US';
        speechSynthesis.speak(utterance);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const englishWordInput = document.getElementById('english_word');
    const suggestionsList = document.getElementById('englishWordSuggestions');

    englishWordInput.addEventListener('input', function () {
        const query = this.value;

        if (query.length > 1) {
            fetch(`/suggestions?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    suggestionsList.innerHTML = '';
                    data.forEach(word => {
                        const listItem = document.createElement('li');
                        listItem.className = 'list-group-item suggestion-item';
                        listItem.textContent = `${word.english_word} - ${word.polish_word}`;
                        listItem.onclick = function () {
                            englishWordInput.value = word.english_word;
                            suggestionsList.innerHTML = '';
                        };
                        suggestionsList.appendChild(listItem);
                    });
                });
        } else {
            suggestionsList.innerHTML = '';
        }
    });
});
