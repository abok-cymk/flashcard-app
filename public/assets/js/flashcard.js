const flashcard = document.querySelector('[data-flashcard]');

if (flashcard) {
    const answer = flashcard.querySelector(
        '[data-flashcard-answer]',
    );

    const toggleButton = flashcard.querySelector(
        '[data-flashcard-toggle]',
    );

    if (answer && toggleButton) {
        answer.hidden = true;
        toggleButton.setAttribute('aria-expanded', 'false');
        toggleButton.textContent = 'Show Answer';

        toggleButton.addEventListener('click', () => {
            const isHidden = answer.hidden;

            answer.hidden = !isHidden;

            toggleButton.setAttribute(
                'aria-expanded',
                String(isHidden),
            );

            toggleButton.textContent = isHidden
                ? 'Hide Answer'
                : 'Show Answer';
        });
    }
}