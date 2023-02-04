<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Repository;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\Session;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;

class SessionRepositoryTest extends TestCase
{
    private UserRepository $userRepository;
    private User $user;
    private Session $session;
    private SessionRepository $sessionRepository;

    public function setUp(): void
    {
        // Initialized
        $connection = Database::getConnection();
        $this->userRepository = new UserRepository($connection);
        $this->sessionRepository = new SessionRepository($connection);

        // Clear Dummy
        $this->sessionRepository->deleteAll();
        $this->userRepository->deleteAll();

        // SetUp Dummy User
        $this->user = new User();
        $this->user->setId("rizal");
        $this->user->setName("rizal");
        $this->user->setPassword("rahasia");

        // Create Session
        $this->session = new Session();
        $this->session->id = uniqid();
        $this->session->user_id = $this->user->getId();
    }
}
