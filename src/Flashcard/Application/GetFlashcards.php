<?php

declare(strict_types=1);

namespace App\Flashcard\Application;

use App\Flashcard\Domain\Flashcard;
use App\Flashcard\Domain\FlashcardRepository;

final readonly class GetFlashcards
{
    public function __construct(
        private FlashcardRepository $flashcards,
    ) {
    }

    /**
     * @return list<Flashcard>
     */
    public function execute(): array
    {
        return $this->flashcards->findAll();
    }
}