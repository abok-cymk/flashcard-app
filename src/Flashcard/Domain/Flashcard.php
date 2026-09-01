<?php

declare(strict_types=1);

namespace App\Flashcard\Domain;

use DateTimeImmutable;

final readonly class Flashcard
{
    public function __construct(
        public string $id,
        public string $question,
        public string $answer,
        public string $category,
        public int $knownCount,
        public DateTimeImmutable $createdAt,
        public DateTimeImmutable $updatedAt,
    ) {
    }
}