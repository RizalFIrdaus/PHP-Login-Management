<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\Session;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\SessionService;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\UserService;



class SessionServiceTest extends TestCase
{
    private SessionService $sessionService;
    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;
    public function setUp(): void
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->sessionService = new SessionService($this->sessionRepository, $this->userRepository);

        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        $user = new User();
        $user->setId("rizal");
        $user->setName("rizal");
        $user->setPassword(password_hash("rahasia", PASSWORD_BCRYPT));
        $this->userRepository->save($user);
    }

    public function testCreate()
    {
        $session = $this->sessionService->create("rizal");
        $this->expectOutputRegex("[X-RIZAL-SESSION : {$session->id}]");
    }
    public function testDestroy()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "rizal";

        $result = $this->sessionRepository->save($session);
        self::assertEquals($session->id, $result->id);
        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;
        $this->expectOutputRegex("[X-RIZAL-SESSION : $session->id]");
        $this->sessionService->destroy();
        $this->expectOutputRegex("[X-RIZAL-SESSION : ]");
    }

    public function testCurrent()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "rizal";
        $this->sessionRepository->save($session);
        $_COOKIE[SessionService::$COOKIE_NAME] = $session->id;
        $user = $this->sessionService->current();
        self::assertEquals("rizal", $user->getId());
    }

    public function testCurrentFailed()
    {
        $_COOKIE[SessionService::$COOKIE_NAME] = "salah";
        $user = $this->sessionService->current();
        self::assertNull($user);
    }
}
