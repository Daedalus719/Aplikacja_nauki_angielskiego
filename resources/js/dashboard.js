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
})
