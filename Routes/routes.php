<?php

use App\Controllers\UserController;
use Core\Helpers\Uri;
use Core\Routes\Route;

$route = new Route();

$route->get('/', function () {
    view('vortex.galaxy.tpl');
});

$route->get('/login', function () {
    view('login.galaxy.tpl', ['home_url' => Uri::getRootPath()]);
});

$route->get('/register', function () {
    view('register.galaxy.tpl', ['home_url' => Uri::getRootPath()]);
});

$route->get('/users', [UserController::class, 'index']);

$route->post('/register', [UserController::class, 'register'])->name('register.post');

$route->post('/login', [UserController::class, 'login']);

$route->get('/user/$id', [UserController::class, 'show']);

$route->default('/404', function () {
    view('404.galaxy.tpl', ['home_url' => Uri::getRootPath()]);
});

$route->mount();
