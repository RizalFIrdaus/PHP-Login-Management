<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;
use ProgrammerZamanNow\Belajar\PHP\MVC\Exception\ValidationException;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserRegistrationRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\UserRegistrationService;

class UserServiceTest extends TestCase
{
    private UserRegistrationService $userService;
    private UserRepository $repository;

    public function setUp(): void
    {
        $connection = Database::getConnection();
        $this->repository = new UserRepository($connection);
        $this->userService = new UserRegistrationService($this->repository);
        $this->repository->deleteAll();
    }

    public function testServiceSuccess()
    {
        $request = new UserRegistrationRequest();
        $request->id = "rizal300500";
        $request->name = "Rizal";
        $request->password = "rahasia123";
        $response = $this->userService->register($request);
        // Checking response not null
        self::assertNotNull($response);
        // Checking request id equals response id
        self::assertEquals($request->id, $response->user->getId());
        // Verify hashing password
        self::assertTrue(password_verify($request->password, $response->user->getPassword()));
    }
    public function testServiceFailed()
    {
        $this->expectException(ValidationException::class);
        $request = new UserRegistrationRequest();
        $request->id = "";
        $request->name = "";
        $request->password = "";
        $this->userService->register($request);
    }

    public function testServiceDuplicate()
    {
        $user = new User();
        $user->setId("esan300500");
        $user->setName("Rizal");
        $user->setPassword("rahasia123");
        $this->repository->save($user);

        $this->expectException(ValidationException::class);

        $request = new UserRegistrationRequest();
        $request->id = "esan300500";
        $request->name = "Rizal";
        $request->password = "rahasia123";
        $this->userService->register($request);
    }
}
