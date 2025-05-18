<?php

$routes = [
    '' => ['HomeController', 'index'],

    // Auth Route
    'login' => ['AuthController', 'login'],
    'register' => ['AuthController', 'register'],

    // Questions Route
    'questions' => ['QuizController', 'questions'],
    'create-question' => ['QuizController', 'createQuestion'],
    'update-question' => ['QuizController', 'updateQuestion'],
    'delete-question' => ['QuizController', 'deleteQuestion'],

    // Options Route
    'options' => ['QuizController', 'options'],
    'create-option' => ['QuizController', 'createOption'],
    'update-option' => ['QuizController', 'updateOption'],
    'delete-option' => ['QuizController', 'deleteOption'],

    // Scores Route
    'scores' => ['ScoreController', 'scores'],
    'create-score' => ['ScoreController', 'createScore'],
];
