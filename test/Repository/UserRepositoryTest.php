<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Repository;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;

use function PHPUnit\Framework\assertNull;

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
}
