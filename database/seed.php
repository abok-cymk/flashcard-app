<?php

declare(strict_types=1);

use App\Infrastructure\Database\DatabaseConnection;

require dirname(__DIR__) . '/vendor/autoload.php';

$jsonPath = __DIR__ . '/seeds/flashcards.json';

$json = file_get_contents($jsonPath);

if ($json === false) {
    throw new RuntimeException(
        sprintf('Unable to read seed file: %s', $jsonPath)
    );
}

$data = json_decode(
    $json,
    true,
    flags: JSON_THROW_ON_ERROR,
);

if (!isset($data['flashcards']) || !is_array($data['flashcards'])) {
    throw new RuntimeException(
        'Seed data must contain a "flashcards" array.'
    );
}

$flashcards = $data['flashcards'];

$pdo = DatabaseConnection::create();

$pdo->beginTransaction();

try {
    $backfillStatement = $pdo->prepare(
        '
        UPDATE flashcards
        SET seed_key = :seed_key
        WHERE seed_key IS NULL
          AND question = :question
          AND answer = :answer
          AND category = :category
        '
    );

    $insertStatement = $pdo->prepare(
        '
        INSERT INTO flashcards (
            seed_key,
            question,
            answer,
            category,
            known_count
        )
        VALUES (
            :seed_key,
            :question,
            :answer,
            :category,
            :known_count
        )
        ON CONFLICT (seed_key) DO NOTHING
        '
    );

    $backfilled = 0;
    $inserted = 0;

    foreach ($flashcards as $flashcard) {
        $backfillStatement->execute([
            'seed_key' => $flashcard['id'],
            'question' => $flashcard['question'],
            'answer' => $flashcard['answer'],
            'category' => $flashcard['category'],
        ]);

        $backfilled += $backfillStatement->rowCount();

        $insertStatement->execute([
            'seed_key' => $flashcard['id'],
            'question' => $flashcard['question'],
            'answer' => $flashcard['answer'],
            'category' => $flashcard['category'],
            'known_count' => $flashcard['knownCount'],
        ]);

        $inserted += $insertStatement->rowCount();
    }

    $pdo->commit();

    echo sprintf(
        'Seed complete: %d backfilled, %d inserted.%s',
        $backfilled,
        $inserted,
        PHP_EOL,
    );
} catch (Throwable $exception) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    throw $exception;
}
