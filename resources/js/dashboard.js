document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchWord');
    const suggestionsList = document.getElementById('wordSuggestions');
    let isEditing = false;

    const userTypeMeta = document.querySelector('meta[name="usertype"]');
    const userType = userTypeMeta ? userTypeMeta.content : '';

    searchInput.addEventListener('input', function () {
        const query = searchInput.value.trim();
        if (query.length > 1) {
            fetch(`/search?query=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => displaySuggestions(data))
                .catch(error => console.error('Error fetching suggestions:', error));
        } else {
            suggestionsList.innerHTML = '';
        }
    });

    function displaySuggestions(words) {
        suggestionsList.innerHTML = '';
        if (words.length === 0) {
            suggestionsList.innerHTML = '<li class="list-group-item">Brak wyników</li>';
            return;
        }
        words.forEach(word => {
            const listItem = document.createElement('li');
            listItem.className = 'list-group-item';
            listItem.textContent = `${word.english_word} - ${word.polish_word}`;
            listItem.addEventListener('click', function () {
                openWordModal(word.id);
            });
            suggestionsList.appendChild(listItem);
        });

        if (words.length > 5) {
            suggestionsList.style.maxHeight = '200px';
            suggestionsList.style.overflowY = 'auto';
        } else {
            suggestionsList.style.maxHeight = '';
            suggestionsList.style.overflowY = '';
        }
    }

    function openWordModal(wordId) {
        fetch(`/words/${wordId}`)
            .then(response => response.json())
            .then(word => {
                isEditing = false;

                const modalTitle = document.getElementById('modalTitle');
                const modalBody = document.getElementById('modalBody');
                const modalFooter = document.getElementById('modalFooter');

                modalTitle.textContent = `${word.english_word} - ${word.polish_word}`;
                modalBody.innerHTML = `
                    <p><strong>Typ słowa:</strong> <span id="wordType">${word.word_type}</span></p>
                    <p><strong>Polskie tłumaczenie:</strong> <span id="polishWord">${word.polish_word}</span></p>
                    <p><strong>Angielskie tłumaczenie:</strong> <span id="englishWord">${word.english_word}</span></p>
                `;

                modalFooter.innerHTML = renderActionButtons(word.id);

                const editButton = document.getElementById('editButton');
                if (editButton) {
                    editButton.addEventListener('click', () => toggleEdit(word));
                }

                const wordModal = new bootstrap.Modal(document.getElementById('wordModal'));
                wordModal.show();
            })
            .catch(error => console.error('Error fetching word details:', error));
    }

    function renderActionButtons(wordId) {

        if (userType === 'Admin' || userType === 'Moderator') {
            return `
                <button class="btn btn-secondary" onclick="window.closeModal()">Zamknij</button>
                <button class="btn btn-primary" id="editButton">Edytuj</button>
                <button class="btn btn-danger" onclick="window.showDeleteConfirmation(${wordId})">Usuń</button>
                          `;
        } else {
            return `
                <button class="btn btn-secondary" onclick="window.closeModal()">Zamknij</button>
            `;
        }
    }

    window.showDeleteConfirmation = function (wordId) {
        const modalBody = document.getElementById('modalBody');
        const modalFooter = document.getElementById('modalFooter');

        modalBody.innerHTML = `<p>Czy jesteś pewien że chcesz usunąć to słowo?</p>`;
        modalFooter.innerHTML = `
            <button class="btn btn-secondary" onclick="window.closeModal()">Anuluj</button>
            <button class="btn btn-danger" onclick="window.confirmDelete(${wordId})">Usuń</button>
        `;
    };

    window.confirmDelete = function (wordId) {
        fetch(`/words/${wordId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeModal();
                } else {
                    alert('Error deleting word: ' + data.error);
                }
            })
            .catch(error => console.error('Error deleting word:', error));
    };

    window.closeModal = function () {
        const wordModal = bootstrap.Modal.getInstance(document.getElementById('wordModal'));
        if (wordModal) wordModal.hide();
    };

    function toggleEdit(word) {
        const editButton = document.getElementById('editButton');
        const wordType = document.getElementById('wordType');
        const polishWord = document.getElementById('polishWord');
        const englishWord = document.getElementById('englishWord');

        if (!isEditing) {
            wordType.innerHTML = `
                <select class="form-element" id="word_type" name="word_type">
                    <option value="Noun (Rzeczownik)" ${word.word_type === 'Noun (Rzeczownik)' ? 'selected' : ''}>Rzeczownik</option>
                    <option value="Verb (Czasownik)" ${word.word_type === 'Verb (Czasownik)' ? 'selected' : ''}>Czasownik</option>
                    <option value="Adjective (Przymiotnik)" ${word.word_type === 'Adjective (Przymiotnik)' ? 'selected' : ''}>Przymiotnik</option>
                    <option value="Adverb (Przysłówek)" ${word.word_type === 'Adverb (Przysłówek)' ? 'selected' : ''}>Przysłówek</option>
                    <option value="Pronoun (Zaimek)" ${word.word_type === 'Pronoun (Zaimek)' ? 'selected' : ''}>Zaimek</option>
                    <option value="Proverb (Przysłowie)" ${word.word_type === 'Proverb (Przysłowie)' ? 'selected' : ''}>Przysłowie</option>
                    <option value="Preposition (Przyimek)" ${word.word_type === 'Preposition (Przyimek)' ? 'selected' : ''}>Przyimek</option>
                    <option value="Conjunction (Spójnik)" ${word.word_type === 'Conjunction (Spójnik)' ? 'selected' : ''}>Spójnik</option>
                    <option value="Interjection (Wykrzyknik)" ${word.word_type === 'Interjection (Wykrzyknik)' ? 'selected' : ''}>Wykrzyknik</option>
                    <option value="Idiom (Idiom)" ${word.word_type === 'Idiom (Idiom)' ? 'selected' : ''}>Idiom</option>
                </select>
            `;
            polishWord.innerHTML = `<input type="text" class="form-control" value="${polishWord.textContent}">`;
            englishWord.innerHTML = `<input type="text" class="form-control" value="${englishWord.textContent}">`;

            editButton.textContent = "Zapisz";
            isEditing = true;
        } else {
            const updatedWord = {
                word_type: wordType.querySelector('select').value,
                polish_word: polishWord.querySelector('input').value,
                english_word: englishWord.querySelector('input').value
            };

            fetch(`/words/${word.id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(updatedWord)
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        wordType.textContent = updatedWord.word_type;
                        polishWord.textContent = updatedWord.polish_word;
                        englishWord.textContent = updatedWord.english_word;

                        editButton.textContent = "Edytuj";
                        isEditing = false;
                    } else {
                        alert('Error saving changes: ' + data.error);
                    }
                })
                .catch(error => console.error('Error updating word:', error));
        }
    }
});
