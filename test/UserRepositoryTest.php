<?php


namespace ProgrammerZamanNow\Belajar\PHP\MVC;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;

class UserRepositoryTest extends TestCase
{
    private UserRepository $userRepository;
    public function setUp(): void
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userRepository->deleteAll();
    }

    public function testSaveSuccess()
    {
        $user = new User();
        $user->setId("rizal");
        $user->setName("rizal");
        $user->setPassword("rahasia");

        $this->userRepository->save($user);
        $result = $this->userRepository->getById($user->getId());
        self::assertNotNull($result);
        self::assertEquals($user->getId(), $result->getId());
    }

    public function testIdNotFound()
    {
        $user = $this->userRepository->getById("awdawd");
        self::assertNull($user);
    }
}
