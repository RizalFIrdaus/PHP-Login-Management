<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Controller;

use ProgrammerZamanNow\Belajar\PHP\MVC\App\View;
use ProgrammerZamanNow\Belajar\PHP\MVC\App\Redirect;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Exception\ValidationException;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserLoginRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserRegistrationRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\SessionService;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\UserService;

class UserController
{
    private UserService $userService;
    private SessionService $sessionService;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository(Database::getConnection());
        $this->userService = new UserService($this->userRepository);
        $sessionRepository = new SessionRepository(Database::getConnection());
        $this->sessionService = new SessionService($sessionRepository, $this->userRepository);
    }

    public function register()
    {
        View::render("User/register", [
            "title" => "Register User"
        ]);
    }

    public function storeRegister()
    {
        // Request get from method post
        $request = new UserRegistrationRequest();
        $request->id = $_POST["id"];
        $request->name = $_POST["name"];
        $request->password = $_POST["password"];

        // trying to call register service if it success then redirecting to user/login
        // else render view register with error message from exception get message
        try {
            $this->userService->register($request);
            Redirect::to("/users/login");
        } catch (ValidationException $exception) {
            View::render("User/register", [
                "title" => "Register User",
                "error" => $exception->getMessage()
            ]);
        }
    }

    public function login()
    {
        View::render("User/login", [
            "title" => "Login User"
        ]);
    }

    public function postLogin()
    {
        $request = new UserLoginRequest();
        $request->id = $_POST["id"];
        $request->password = $_POST["password"];

        try {
            $response = $this->userService->login($request);
            $this->sessionService->create($response->user->getId());
            Redirect::to("/");
        } catch (ValidationException $exception) {
            View::render("User/login", [
                "title" => "Login User",
                "error" => $exception->getMessage()
            ]);
        }
    }

    public function logout()
    {
        $this->sessionService->destroy();
        Redirect::to("/");
    }

    public function profile()
    {
        $response = $this->sessionService->current();
        if ($response != null) {
            View::render("User/profile", [
                "title" => "Profile",
                "user" => [
                    "id" => $response->getId(),
                    "name" => $response->getName()
                ]
            ]);
        } else {
            Redirect::to("/");
        }
    }
}
