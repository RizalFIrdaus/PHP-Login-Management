<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Repository;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;


class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;
    private User $user;
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
    }

    public function testSaveSuccess()
    {
        $response = $this->userRepository->save($this->user);
        self::assertNotNull($response);
        self::assertEquals($this->user->getId(), $response->getId());
        self::assertEquals($this->user->getName(), $response->getName());
        self::assertEquals($this->user->getPassword(), $response->getPassword());
    }
    public function testSaveFailed()
    {
        $user = new User();
        $response = $this->userRepository->save($user);
        self::assertNull($response);
    }

    public function testGetByIdSuccess()
    {
        // Create User
        $this->userRepository->save($this->user);
        // Get Id
        $response = $this->userRepository->getById($this->user->getId());
        // Expect response given User that means not null 
        self::assertNotNull($response);
        self::assertEquals($this->user->getId(), $response->getId());
        self::assertEquals($this->user->getName(), $response->getName());
        self::assertEquals($this->user->getPassword(), $response->getPassword());
    }

    public function testGetByIdNotFound()
    {
        $response = $this->userRepository->getById("wrong id");
        self::assertNull($response);
    }

    public function testUpdateProfileSucess()
    {
        // Create user
        $this->userRepository->save($this->user);

        // Get Identity User
        $id = $this->userRepository->getById($this->user->getId());

        // Update by identity user
        $response = $this->userRepository->update($id);
        self::assertNotNull($response);
    }
    public function testUpdateProfileIdNotFound()
    {
        $id = $this->userRepository->getById("Wrong Id");
        // Update by identity user
        $response = $this->userRepository->update($id);
        self::assertNull($response);
    }
}
