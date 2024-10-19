import { showMessage } from './messageHandler.js';



//code for removal from here

document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchWord');
    const suggestionsList = document.getElementById('wordSuggestions');
    const wordTableBody = document.getElementById('wordTableBody');
    const englishWordInput = document.getElementById('english_word');
    const englishWordSuggestionsList = document.getElementById('englishWordSuggestions');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    let offset = 60;
    let isLoading = false;

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


    englishWordInput.addEventListener('input', function () {
        const query = this.value;

        if (query.length > 1) {
            fetch(`/search-words?query=${query}`)
                .then(response => response.json())
                .then(data => {
                    englishWordSuggestionsList.innerHTML = '';

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
                                englishWordInput.value = word.english_word;
                                englishWordSuggestionsList.innerHTML = '';
                            });
                            englishWordSuggestionsList.appendChild(listItem);
                        });
                    } else {
                        const noResult = document.createElement('li');
                        noResult.classList.add('list-group-item');
                        noResult.textContent = 'No results found';
                        englishWordSuggestionsList.appendChild(noResult);
                    }
                });
        } else {
            englishWordSuggestionsList.innerHTML = '';
        }
    });

    document.addEventListener('click', function (event) {
        if (!suggestionsList.contains(event.target) && event.target !== searchInput) {
            suggestionsList.innerHTML = '';
        }

        if (!englishWordSuggestionsList.contains(event.target) && event.target !== englishWordInput) {
            englishWordSuggestionsList.innerHTML = '';
        }
    });

    //till here




    /*     THE RIGHT CODE WITHOIT THE ADDED SEARCH BAR TO TH ADD WORDS FORM (TO BE RESTORED!!!!)


    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchWord');
        const suggestionsList = document.getElementById('wordSuggestions');
        const wordTableBody = document.getElementById('wordTableBody');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        let offset = 60;
        let isLoading = false;

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
        });*/


    let wordIdToDelete = null;

    wordTableBody.addEventListener('click', function (e) {
        if (e.target.closest('button') && e.target.closest('button').classList.contains('btn-outline-danger')) {
            e.preventDefault();
            const deleteButton = e.target.closest('button');
            wordIdToDelete = deleteButton.closest('form').action.split('/').pop();

            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
            deleteModal.show();
        }
    });

    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    confirmDeleteBtn.addEventListener('click', function () {
        if (wordIdToDelete) {
            const form = document.querySelector(`form[action$="/${wordIdToDelete}"]`);
            if (form) {
                form.submit();
            }
        }
    });

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

    function createWordRow(word) {
        const row = document.createElement('tr');
        row.id = `word-row-${word.id}`;

        row.innerHTML = `
            <td class="english-word">${word.english_word}</td>
            <td class="pronunciation">
                ${word.pronunciation !== null ? word.pronunciation : ''}
            </td>
            <td class="word-type">${capitalize(word.word_type)}</td>
            <td class="polish-word">${word.polish_word}</td>
            <td>${renderActionButtons(word.id)}</td>`;

        const ttsButton = document.createElement('button');
        ttsButton.classList.add('btn', 'btn-sm', 'btn-outline-primary', 'tts-btn');
        ttsButton.textContent = '🔊';
        ttsButton.setAttribute('data-english-word', word.english_word);

        const pronunciationCell = row.querySelector('.pronunciation');
        pronunciationCell.appendChild(ttsButton);

        ttsButton.addEventListener('click', function () {
            const englishWord = this.getAttribute('data-english-word');
            const cleanedWord = cleanText(englishWord);
            speak(cleanedWord);
        });

        return row;
    }


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
            return '';
        }
    }

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

        if (e.target.classList.contains('cancel-btn')) {
            const wordId = e.target.getAttribute('data-word-id');
            const row = document.getElementById(`word-row-${wordId}`);

            row.querySelector('.english-word').textContent = row.dataset.originalEnglishWord;
            row.querySelector('.word-type').textContent = capitalize(row.dataset.originalWordType);
            row.querySelector('.polish-word').textContent = row.dataset.originalPolishWord;

            toggleEditMode(row, false);
        }
    });


    function toggleEditMode(row, isEditing) {
        const wordId = row.id.split('-').pop();

        if (isEditing) {
            row.dataset.originalEnglishWord = row.querySelector('.english-word').textContent.trim();
            row.dataset.originalWordType = row.querySelector('.word-type').textContent.toLowerCase();
            row.dataset.originalPolishWord = row.querySelector('.polish-word').textContent.trim();

            row.classList.add('editable-row');
            row.querySelector('.english-word').innerHTML = `<input type="text" class="form-control" value="${row.dataset.originalEnglishWord}" id="english-word-${wordId}">`;
            row.querySelector('.word-type').innerHTML = `
            <select class="form-control" id="word-type-${wordId}">
                <option value="Noun (Rzeczownik)" ${row.dataset.originalWordType === 'noun (rzeczownik)' ? 'selected' : ''}>Noun (Rzeczownik)</option>
                <option value="Verb (Czasownik)" ${row.dataset.originalWordType === 'verb (czasownik)' ? 'selected' : ''}>Verb (Czasownik)</option>
                <option value="Adjective (Przymiotnik)" ${row.dataset.originalWordType === 'adjective (przymiotnik)' ? 'selected' : ''}>Adjective (Przymiotnik)</option>
                <option value="Adverb (Przysłówek)" ${row.dataset.originalWordType === 'adverb (przysłówek)' ? 'selected' : ''}>Adverb (Przysłówek)</option>
                <option value="Pronoun (Zaimek)" ${row.dataset.originalWordType === 'pronoun (zaimek)' ? 'selected' : ''}>Pronoun (Zaimek)</option>
                <option value="Proverb (Przysłowie)" ${row.dataset.originalWordType === 'proverb (przysłowie)' ? 'selected' : ''}>Proverb (Przysłowie)</option>
                <option value="Preposition (Przyimek)" ${row.dataset.originalWordType === 'preposition (przyimek)' ? 'selected' : ''}>Preposition (Przyimek)</option>
                <option value="Conjunction (Spójnik)" ${row.dataset.originalWordType === 'conjunction (spójnik)' ? 'selected' : ''}>Conjunction (Spójnik)</option>
                <option value="Interjection (Wykrzyknik)" ${row.dataset.originalWordType === 'interjection (wykrzyknik)' ? 'selected' : ''}>Interjection (Wykrzyknik)</option>
                <option value="Idiom (Idiom)" ${row.dataset.originalWordType === 'idiom (idiom)' ? 'selected' : ''}>Idiom (Idiom)</option>
            </select>`;

            row.querySelector('.polish-word').innerHTML = `<input type="text" class="form-control" value="${row.dataset.originalPolishWord}" id="polish-word-${wordId}">`;

            row.querySelector('.edit-btn').style.display = 'none';
            row.querySelector('.save-btn').style.display = 'inline';

            if (!row.querySelector('.cancel-btn')) {
                const cancelButton = document.createElement('button');
                cancelButton.classList.add('btn', 'btn-sm', 'btn-outline-warning', 'cancel-btn');
                cancelButton.textContent = 'Anuluj';
                cancelButton.setAttribute('data-word-id', wordId);
                row.querySelector('td:last-child').appendChild(cancelButton);
            }
        } else {
            row.querySelector('.english-word').textContent = row.dataset.originalEnglishWord;
            row.querySelector('.word-type').textContent = capitalize(row.dataset.originalWordType);
            row.querySelector('.polish-word').textContent = row.dataset.originalPolishWord;

            row.classList.remove('editable-row');
            row.querySelector('.edit-btn').style.display = 'inline';
            row.querySelector('.save-btn').style.display = 'none';

            const cancelButton = row.querySelector('.cancel-btn');
            if (cancelButton) {
                cancelButton.remove();
            }
        }
    }


    function saveWordChanges(row, wordId) {
        const englishWord = document.getElementById(`english-word-${wordId}`).value;
        const wordType = document.getElementById(`word-type-${wordId}`).value;
        const polishWord = document.getElementById(`polish-word-${wordId}`).value;

        const data = {
            english_word: englishWord,
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
                    toggleEditMode(row, false);
                } else {
                    alert('Wystąpił błąd podczas zapisawanie słowa.');
                }
            })
            .catch(error => {
                console.error('Wystąpił błąd podczas zapisawanie słowa:', error);
            });
    }

    window.addEventListener('scroll', function () {
        if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 50) {
            loadMoreWords();
        }
    });

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
    }
});




document.addEventListener('DOMContentLoaded', function () {

    const ttsButtons = document.querySelectorAll('.tts-btn');

    ttsButtons.forEach(button => {
        button.addEventListener('click', function () {
            const englishWord = this.getAttribute('data-english-word');
            const cleanedWord = cleanText(englishWord);
            speak(cleanedWord);
        });
    });

    function cleanText(text) {
        return text.replace(/[\/]/g, ' ');
    }


    function speak(text) {
        if ('speechSynthesis' in window) {
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'en-US';
            utterance.rate = 1;
            utterance.pitch = 1;
            window.speechSynthesis.speak(utterance);
        } else {
            alert('Sorry, your browser does not support text-to-speech.');
        }
    }
});

