<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Controller;

use ProgrammerZamanNow\Belajar\PHP\MVC\App\View;
use ProgrammerZamanNow\Belajar\PHP\MVC\App\Redirect;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Exception\ValidationException;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserRegistrationRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Service\UserRegistrationService;

class UserController
{
    private UserRegistrationService $userService;

    public function __construct()
    {
        $connection = Database::getConnection();
        $repository = new UserRepository($connection);
        $this->userService = new UserRegistrationService($repository);
    }

    public function index()
    {
        View::render("User/register", [
            "title" => "Register User"
        ]);
    }

    public function store()
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
            // Redirect::to("/users/login");
        } catch (ValidationException $exception) {
            View::render("User/register", [
                "title" => "Register User",
                "error" => $exception->getMessage()
            ]);
        }
    }
}
