<?php


namespace ProgrammerZamanNow\Belajar\PHP\MVC;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\UserService;

class updatePasswordTest extends TestCase
{
    private UserService $userService;
    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;


    public function setUp(): void
    {
        $this->userService = new UserService($this->userRepository);
    }

    public function testUpdatePasswordSuccess()
    {

        // $this->userService->updatePassword();
    }
    public function testUpdatePasswordFailed()
    {
    }
}
