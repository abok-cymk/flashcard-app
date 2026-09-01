<?php

declare(strict_types=1);

namespace App\Flashcard\Application;

use App\Flashcard\Domain\FlashcardRepository;

final readonly class GetStudyFlashcard
{
    public function __construct(
        private FlashcardRepository $flashcards,
    ) {}

    public function execute(int $position = 1): StudyFlashcard
    {
        $total = $this->flashcards->count();

        if ($total === 0) {
            return new StudyFlashcard(
                flashcard: null,
                position: 1,
                total: 0,
                hasPrevious: false,
                hasNext: false,
            );
        }

        $position = max(
            1,
            min($position, $total),
        );

        return new StudyFlashcard(
            flashcard: $this->flashcards->findByPosition($position),
            position: $position,
            total: $total,
            hasPrevious: $position > 1,
            hasNext: $position < $total,
        );
    }
}
