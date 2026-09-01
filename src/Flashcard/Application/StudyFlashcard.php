<?php

declare(strict_types=1);

namespace App\Flashcard\Application;

use App\Flashcard\Domain\Flashcard;

final readonly class StudyFlashcard
{
    public function __construct(
        public ?Flashcard $flashcard,
        public int $position,
        public int $total,
        public bool $hasPrevious,
        public bool $hasNext,
    ) {}
}
