<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\SessionService;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\UserService;



class SessionServiceTest extends TestCase
{
    private SessionService $sessionService;
    private UserService $userService;
    public function setUp(): void
    {
        $session = new SessionRepository(Database::getConnection());
        $user = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($session, $user);
    }

    public function testCreate()
    {
        $session = $this->sessionService->create("rizal300500");
        $this->expectOutputRegex("[X-RIZAL-SESSION : {$session->id}]");
    }
}
