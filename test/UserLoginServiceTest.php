<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;
use ProgrammerZamanNow\Belajar\PHP\MVC\Exception\ValidationException;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserLoginRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserLoginResponse;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\UserService;

class UserLoginServiceTest extends TestCase
{
    private UserService $userService;
    public function setUp(): void
    {
        $repo = new UserRepository(Database::getConnection());
        $this->userService = new UserService($repo);
        // $repo->deleteAll();
    }

    public function testLoginSuccess()
    {
        $request = new UserLoginRequest();
        $request->id = "rizal300500";
        $request->password = "rizal12345";
        $login = $this->userService->login($request);
        self::assertEquals($request->id, $login->user->getId());
    }

    public function testLoginFailed()
    {
        $this->expectException(ValidationException::class);
        $request = new UserLoginRequest();
        $this->userService->login($request);
    }
}
