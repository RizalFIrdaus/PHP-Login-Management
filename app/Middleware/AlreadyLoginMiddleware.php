<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Middleware;

use ProgrammerZamanNow\Belajar\PHP\MVC\App\Redirect;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Middleware\Middleware;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\SessionService;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\UserService;


class AlreadyLoginMiddleware implements Middleware
{
    private UserService $userService;
    private UserRepository $userRepository;
    private SessionService $sessionService;
    private SessionRepository $sessionRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);
    }
    public function before(): void
    {
        $response = $this->sessionService->current();
        if ($response) {
            Redirect::to("/");
        }
    }
}
