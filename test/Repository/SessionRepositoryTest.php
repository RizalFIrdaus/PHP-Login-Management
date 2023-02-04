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

    public function testSaveSuccess()
    {
        // Create User
        $this->userRepository->save($this->user);
        // Create Session
        $response = $this->sessionRepository->save($this->session);
        self::assertNotNull($response);
        self::assertEquals($this->session->id, $response->id);
        self::assertEquals($this->session->user_id, $response->user_id);
    }

    public function testSaveFailed()
    {
        $response = $this->sessionRepository->save();
        self::assertNull($response);
    }

    public function testGetByIdSuccess()
    {
        // Create User
        $this->userRepository->save($this->user);
        // Create Session
        $this->sessionRepository->save($this->session);
        // GetById Session
        $response = $this->sessionRepository->getById($this->session->id);
        self::assertNotNull($response);
        self::assertEquals($this->session->id, $response->id);
        self::assertEquals($this->session->user_id, $response->user_id);
    }

    public function testGetByIdFailed()
    {
        $response = $this->sessionRepository->getById();
        self::assertNull($response);
    }

    public function testDeleteByIdSuccess()
    {
        // Create User
        $this->userRepository->save($this->user);
        // Create Session
        $this->sessionRepository->save($this->session);
        // DeleteById Session
        $response = $this->sessionRepository->deleteById($this->session->id);
        self::assertNotNull($response);
        self::assertTrue($response);
    }

    public function testDeleteByIdFailed()
    {
        $response = $this->sessionRepository->deleteById();
        self::assertNotNull($response);
        self::assertFalse($response);
    }
}
