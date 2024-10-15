document.addEventListener("DOMContentLoaded", function () {
    const card = document.getElementById("card");
    const polishWordElem = document.getElementById("polish-word");
    const englishWordElem = document.getElementById("english-word");
    const prevButton = document.getElementById("prev");
    const nextButton = document.getElementById("next");

    let currentIndex = 0;
    let isFlipped = false;

    const words = window.wordsData;

    function updateCard() {
        const currentWord = words[currentIndex];
        polishWordElem.textContent = currentWord.polish_word;
        englishWordElem.textContent = currentWord.english_word;
        card.classList.remove("flipped");

        prevButton.disabled = currentIndex === 0;
        nextButton.disabled = currentIndex === words.length - 1;

        if (prevButton.disabled) {
            prevButton.classList.add("disabled");
        } else {
            prevButton.classList.remove("disabled");
        }

        if (nextButton.disabled) {
            nextButton.classList.add("disabled");
        } else {
            nextButton.classList.remove("disabled");
        }

        isFlipped = false;
    }

    card.addEventListener("click", function () {
        if (isFlipped) {
            card.classList.remove("flipped");
        } else {
            card.classList.add("flipped");
        }
        isFlipped = !isFlipped;
    });

    prevButton.addEventListener("click", function () {
        if (currentIndex > 0) {
            currentIndex--;
            updateCard();
        }
    });

    nextButton.addEventListener("click", function () {
        if (currentIndex < words.length - 1) {
            currentIndex++;
            updateCard();
        }
    });

    updateCard();
});
