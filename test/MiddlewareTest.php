<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\Session;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;
use ProgrammerZamanNow\Belajar\PHP\MVC\Middleware\MustLoginMiddleware;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\SessionService;


class MiddlewareTest extends TestCase
{
    private SessionService $sessionService;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;
    private User $user;

    public function setUp(): void
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);
        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $this->user = new User();
        $this->user->setId("rizal12345");
        $this->user->setName("rizal");
        $this->user->setPassword("rahasia12345");
        $this->userRepository->save($this->user);
    }

    public function testMustLoginMiddleware()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = $this->user->getId();
        $this->sessionService->create($session->user_id);
        $mustLoginMiddleware = new MustLoginMiddleware();
        $response = $mustLoginMiddleware->before();
        $this->expectOutputRegex("[X-RIZAL-SESSION] : rizal12345");
    }
    public function testAlreadyLoginMiddleware()
    {
    }
}
