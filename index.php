<?php
require_once 'config/database.php';
require_once 'routes.php';

// 
require_once 'controllers/AuthController.php'; 
require_once 'controllers/HomeController.php'; 

$url = isset($_GET['url']) ? rtrim($_GET['url'], '/') : '';
$url = filter_var($url, FILTER_SANITIZE_URL);

if (array_key_exists($url, $routes)) {
    list($controllerName, $method) = $routes[$url];
    $controller = new $controllerName();
    call_user_func([$controller, $method]);
} else {
    http_response_code(404);
    echo "404 - Halaman tidak ditemukan";
}
