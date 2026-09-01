<?php

declare(strict_types=1);

namespace App\Flashcard\Domain;

interface FlashcardRepository
{
    /**
     * @return list<Flashcard>
     */
    public function findAll(): array;

    public function findFirst(): ?Flashcard;

    public function findByPosition(int $position): ?Flashcard;
    
    public function count(): int;

}