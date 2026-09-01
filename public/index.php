<?php

declare(strict_types=1);

use App\Flashcard\Application\GetStudyFlashcard;
use App\Flashcard\Infrastructure\Persistence\PostgresFlashcardRepository;
use App\Infrastructure\Database\DatabaseConnection;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require dirname(__DIR__) . '/vendor/autoload.php';

$connection = DatabaseConnection::create();

$flashcardRepository = new PostgresFlashcardRepository(
    $connection,
);

$getStudyFlashcard = new GetStudyFlashcard(
    $flashcardRepository,
);

$position = filter_input(
    INPUT_GET,
    'card',
    FILTER_VALIDATE_INT,
    [
        'options' => [
            'min_range' => 1,
        ],
    ],
);

$position = $position === false || $position === null
    ? 1
    : $position;

$studyFlashcard = $getStudyFlashcard->execute(
    $position,
);

$loader = new FilesystemLoader(
    dirname(__DIR__) . '/templates'
);

$twig = new Environment($loader);

echo $twig->render('home.twig', [
    'flashcard' => $studyFlashcard->flashcard,
    'position' => $studyFlashcard->position,
    'total' => $studyFlashcard->total,
    'hasPrevious' => $studyFlashcard->hasPrevious,
    'hasNext' => $studyFlashcard->hasNext,
]);
