let isAnswerVisible = false;

function syncFlashcardUI() {
    const flashcard = document.querySelector('[data-flashcard]');
    if (!flashcard) return;

    const answer = flashcard.querySelector('[data-flashcard-answer]');
    const toggleButton = flashcard.querySelector('[data-flashcard-toggle]');

    if (answer && toggleButton) {
        answer.hidden = !isAnswerVisible;
        toggleButton.setAttribute('aria-expanded', String(isAnswerVisible));
        toggleButton.textContent = isAnswerVisible ? 'Hide Answer' : 'Show Answer';
    }
}

document.addEventListener('click', (event) => {
    const toggleButton = event.target.closest('[data-flashcard-toggle]');
    if (!toggleButton) return;

    isAnswerVisible = !isAnswerVisible;
    syncFlashcardUI();
});

syncFlashcardUI();
if (window.swup) {
    window.swup.hooks.on('page:view', () => {
        syncFlashcardUI();
    });
}