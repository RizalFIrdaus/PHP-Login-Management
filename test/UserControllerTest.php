<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Controller\UserController;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;

class UserControllerTest extends TestCase
{
    private UserController $userController;
    private UserRepository $repository;

    public function setUp(): void
    {
        $this->userController = new UserController();
        $connection = Database::getConnection();
        $this->repository = new UserRepository($connection);
        $this->repository->deleteAll();
    }

    public function testRegister()
    {
        $this->userController->index();
        $this->expectOutputRegex("[Register]");
        $this->expectOutputRegex("[Muhammad Rizal Firdaus]");
        $this->expectOutputRegex("[Name]");
        $this->expectOutputRegex("[Id]");
        $this->expectOutputRegex("[Password]");
    }

    public function testStoreRegisterSuccess()
    {
        $_POST["id"] = "rizal300500";
        $_POST["name"] = "rizal";
        $_POST["password"] = "rahasia12345";
        $this->userController->store();
        $this->expectOutputRegex("[]");
    }

    public function testStoreRegisterFailedBlank()
    {
        $_POST["id"] = "";
        $_POST["name"] = "";
        $_POST["password"] = "";
        $this->userController->store();
        $this->expectOutputRegex("[Id,name or password can't blank]");
    }

    public function testStoreRegisterFailedNull()
    {
        $this->userController->store();
        $this->expectOutputRegex("[Id,name or password can't blank]");
    }

    public function testStoreRegisterDuplicate()
    {
        $user = new User();
        $user->setId("rizal300500");
        $user->setName("Rizal");
        $user->setPassword("rahasia12345");
        $this->repository->save($user);


        $_POST["id"] = "rizal300500";
        $_POST["name"] = "Rizal";
        $_POST["password"] = "rahasia12345";
        $this->userController->store();
        $this->expectOutputRegex("[Id rizal300500 already exist !]");
    }
}
