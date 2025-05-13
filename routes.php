<?php

$routes = [
    '' => ['HomeController', 'index'],
    'login' => ['AuthController', 'login'],
    'register' => ['AuthController', 'register'],
    'questions' => ['QuizController', 'questions'],
    'options' => ['QuizController', 'options'],
];
