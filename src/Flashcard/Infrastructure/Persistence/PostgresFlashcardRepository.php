<?php

declare(strict_types=1);

namespace App\Flashcard\Infrastructure\Persistence;

use App\Flashcard\Domain\Flashcard;
use App\Flashcard\Domain\FlashcardRepository;
use DateTimeImmutable;
use PDO;

final readonly class PostgresFlashcardRepository implements FlashcardRepository
{
    public function __construct(
        private PDO $connection,
    ) {}

    /**
     * @return list<Flashcard>
     */
    public function findAll(): array
    {
        $statement = $this->connection->query(
            '
            SELECT
                id,
                question,
                answer,
                category,
                known_count,
                created_at,
                updated_at
            FROM flashcards
            ORDER BY created_at ASC
            '
        );

        $rows = $statement->fetchAll();

        return array_map(
            /**
             * @param array{
             *     id: string,
             *     question: string,
             *     answer: string,
             *     category: string,
             *     known_count: int|string,
             *     created_at: string,
             *     updated_at: string
             * } $row
             */
            static fn(array $row): Flashcard => new Flashcard(
                id: $row['id'],
                question: $row['question'],
                answer: $row['answer'],
                category: $row['category'],
                knownCount: (int) $row['known_count'],
                createdAt: new DateTimeImmutable($row['created_at']),
                updatedAt: new DateTimeImmutable($row['updated_at']),
            ),
            $rows,
        );
    }

    public function findFirst(): ?Flashcard
    {
        $statement = $this->connection->query(
            '
        SELECT
            id,
            question,
            answer,
            category,
            known_count,
            created_at,
            updated_at
        FROM flashcards
        ORDER BY created_at ASC
        LIMIT 1
        '
        );

        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

      return $this->mapToFlashcard($row);
    }

    public function findByPosition(int $position): ?Flashcard
    {
        if ($position < 1) {
            return null;
        }

        $statement = $this->connection->prepare(
            '
        SELECT
            id,
            question,
            answer,
            category,
            known_count,
            created_at,
            updated_at
        FROM flashcards
        ORDER BY created_at ASC, id ASC
        LIMIT 1
        OFFSET :offset
        '
        );

        $statement->bindValue(
            'offset',
            $position - 1,
            PDO::PARAM_INT,
        );

        $statement->execute();

        $row = $statement->fetch();

        if ($row === false) {
            return null;
        }

      return $this->mapToFlashcard($row);               
    }

    public function count(): int
    {
        $statement = $this->connection->query(
            '
        SELECT COUNT(*)
        FROM flashcards
        '
        );

        return (int) $statement->fetchColumn();
    }

    /**
     * @param array<string, mixed> $row
     */
    private function mapToFlashcard(array $row): Flashcard
    {
        return new Flashcard(
            id: $row['id'],
            question: $row['question'],
            answer: $row['answer'],
            category: $row['category'],
            knownCount: (int) $row['known_count'],
            createdAt: new DateTimeImmutable($row['created_at']),
            updatedAt: new DateTimeImmutable($row['updated_at']),
        );
    }
}
