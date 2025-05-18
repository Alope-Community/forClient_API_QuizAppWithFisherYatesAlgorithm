<?php

$routes = [
    '' => ['HomeController', 'index'],
    'login' => ['AuthController', 'login'],
    'register' => ['AuthController', 'register'],
    'questions' => ['QuizController', 'questions'],
    'create-question' => ['QuizController', 'createQuestion'],
    'update-question' => ['QuizController', 'updateQuestion'],
    'delete-question' => ['QuizController', 'deleteQuestion'],
    'options' => ['QuizController', 'options'],
    'create-option' => ['QuizController', 'createOption'],
];
