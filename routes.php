<?php

$routes = [
    '' => ['HomeController', 'index'],
    'login' => ['AuthController', 'login'],
    'register' => ['AuthController', 'register'],
    'questions' => ['QuizController', 'questions'],
    'create-question' => ['QuizController', 'createQuestion'],
    'options' => ['QuizController', 'options'],
    'create-option' => ['QuizController', 'createOption'],
];
