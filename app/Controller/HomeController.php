<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Controller;

use ProgrammerZamanNow\Belajar\PHP\MVC\App\View;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\SessionService;

class HomeController
{
    private SessionService $sessionService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $userRepository = new UserRepository($connection);
        $sessionRepository = new SessionRepository($connection);
        $this->sessionService = new SessionService($sessionRepository, $userRepository);
    }

    function index()
    {
        $response = $this->sessionService->current();
        if ($response == null) {
            View::render("Home/index", [
                "title" => "Login"
            ]);
        } else {
            View::render("Home/dashboard", [
                "title" => "Dashboard",
                "user" => [
                    "name" => $response->username
                ]
            ]);
        }
    }
}
