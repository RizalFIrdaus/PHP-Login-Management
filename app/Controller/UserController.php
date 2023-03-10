<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Controller;

use ProgrammerZamanNow\Belajar\PHP\MVC\App\View;
use ProgrammerZamanNow\Belajar\PHP\MVC\App\Redirect;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Exception\ValidationException;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserLoginRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserPasswordRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserProfileRequest;
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
        View::render("User/profile", [
            "title" => "Profile",
            "user" => [
                "id" => $response->session_id,
                "name" => $response->username
            ]
        ]);
    }

    public function postProfile()
    {
        $response = $this->sessionService->current();
        $request = new UserProfileRequest();
        $request->id = $response->session_id;
        $request->name = $_POST["name"];
        try {
            $this->userService->updateProfile($request);
            Redirect::to("/");
        } catch (ValidationException $exception) {
            View::render("User/profile", [
                "title" => "Profile",
                "error" => $exception->getMessage(),
                "user" => [
                    "id" => $response->session_id,
                    "name" => $_POST["name"]
                ]
            ]);
        }
    }

    public function password()
    {
        $response = $this->sessionService->current();

        View::render("User/password", [
            "title" => "Change Password",
            "user" => [
                "id" => $response->session_id,
                "name" => $response->username
            ]
        ]);
    }

    public function postPassword()
    {
        $response = $this->sessionService->current();
        $request = new UserPasswordRequest();
        $request->id = $response->session_id;
        $request->oldPassword = $_POST["oldPassword"];
        $request->newPassword = $_POST["newPassword"];
        try {
            $this->userService->updatePassword($request);
            Redirect::to("/");
        } catch (ValidationException $exception) {
            View::render("User/password", [
                "title" => "Change Password",
                "error" => $exception->getMessage(),
                "user" => [
                    "id" => $response->session_id,
                    "newPassword" => $_POST["newPassword"],
                    "oldPassword" => $_POST["oldPassword"]
                ]
            ]);
        }
    }
}
