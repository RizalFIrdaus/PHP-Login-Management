<?php

require_once __DIR__ . '/../vendor/autoload.php';

use ProgrammerZamanNow\Belajar\PHP\MVC\App\Router;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Controller\HomeController;
use ProgrammerZamanNow\Belajar\PHP\MVC\Controller\UserController;
use ProgrammerZamanNow\Belajar\PHP\MVC\Middleware\AlreadyLoginMiddleware;
use ProgrammerZamanNow\Belajar\PHP\MVC\Middleware\MustLoginMiddleware;

Database::getConnection("prod");

Router::add('GET', '/', HomeController::class, 'index');
Router::add('GET', '/users/register', UserController::class, 'register', [AlreadyLoginMiddleware::class]);
Router::add('POST', '/users/register', UserController::class, 'storeRegister', [AlreadyLoginMiddleware::class]);
Router::add('GET', '/users/login', UserController::class, 'login', [AlreadyLoginMiddleware::class]);
Router::add('POST', '/users/login', UserController::class, 'postLogin', [AlreadyLoginMiddleware::class]);
Router::add('GET', '/users/logout', UserController::class, 'logout', [MustLoginMiddleware::class]);
Router::add('GET', '/users/profile', UserController::class, 'profile', [MustLoginMiddleware::class]);
Router::add('POST', '/users/profile', UserController::class, 'postProfile', [MustLoginMiddleware::class]);
Router::add('GET', '/users/password', UserController::class, 'password', [MustLoginMiddleware::class]);
Router::add('POST', '/users/password', UserController::class, 'postPassword', [MustLoginMiddleware::class]);

Router::run();
