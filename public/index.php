<?php
// public/index.php

// 1) Autoloader
spl_autoload_register(function($class) {
    $prefix   = 'App\\';
    $baseDir  = __DIR__ . '/../app/';
    $len      = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) return;
    $relClass = substr($class, $len);
    $file     = $baseDir . str_replace('\\', '/', $relClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// 2) Config + session
require __DIR__ . '/../config/database.php';
session_start();

// 3) Parse incoming request
$uri        = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method     = $_SERVER['REQUEST_METHOD'];
$basePath   = '/freelance-website-mvc/public';  // <-- your actual docroot
$path       = substr($uri, strlen($basePath)) ?: '/';

// 4) Define your routes map
$routes = [

    // Public / anonymous
    'GET' => [
        '/'                    => ['App\Controllers\HomeController','index'],
        '/login'               => ['App\Controllers\AuthController','showLogin'],
        '/register'            => ['App\Controllers\AuthController','showRegister'],
        '/gigs'                => ['App\Controllers\GigController','index'],
        '/gigs/create'         => ['App\Controllers\GigController','create'],
        '/gigs/(\d+)'          => ['App\Controllers\GigController','show'],
    ],
    'POST' => [
        '/login'               => ['App\Controllers\AuthController','login'],
        '/register'            => ['App\Controllers\AuthController','register'],
        '/gigs'                => ['App\Controllers\GigController','store'],
    ],

    // Buyer-only
    'GET'  => [
        '/orders'              => ['App\Controllers\OrderController','buyerOrders'],
        '/search'              => ['App\Controllers\SearchController','index'],
    ],
    'POST' => [
        // …
    ],

    // Seller-only
    'GET'  => [
        '/dashboard/seller'    => ['App\Controllers\DashboardController','seller'],
        '/my_ratings'          => ['App\Controllers\DashboardController','myRatings'],
    ],

    // Admin-only
    'GET'  => [
        '/admin'               => ['App\Controllers\AdminController','index'],
        '/admin/complaints'    => ['App\Controllers\AdminController','complaints'],
        '/admin/top-sellers'   => ['App\Controllers\AdminController','topSellers'],
    ],
    'POST' => [
        // …
    ],
];

// 5) Match the route
$found = false;
foreach ($routes[$method] as $route => $handler) {
    if (preg_match('@^' . $route . '$@', $path, $matches)) {
        array_shift($matches); // full match
        [$controller, $action] = $handler;
        call_user_func_array([new $controller, $action], $matches);
        $found = true;
        break;
    }
}

if (!$found) {
    http_response_code(404);
    echo "Page not found.";
}
