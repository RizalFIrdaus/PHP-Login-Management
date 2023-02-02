<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Service;

use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;
use ProgrammerZamanNow\Belajar\PHP\MVC\Exception\ValidationException;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserRegistrationRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserRegistrationResponse;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;

class UserRegistrationService
{
    private UserRepository $userRepository;

    // Injection Repository
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    // Registration Bussiness Logic
    public function register(UserRegistrationRequest $request): UserRegistrationResponse
    {
        // Validation
        $this->validationUserRegistrationRequest($request);

        try {
            Database::beginTransaction();
            $user = $this->userRepository->getById($request->id);
            // Checking if user already exist
            if ($user != null) {
                throw new ValidationException("Id $request->id already exist !");
            }
            // Create new user if id ready to save
            $user = new User();
            $user->setId($request->id);
            $user->setName($request->name);
            $user->setPassword(password_hash($request->password, PASSWORD_BCRYPT));
            $this->userRepository->save($user);

            // Return response
            $response = new UserRegistrationResponse();
            $response->user = $user;
            return $response;

            Database::commitTransaction();
        } catch (\Exception $exception) {
            Database::rollbackTransaction();
            throw $exception;
        }
    }


    /**
     * Validation Method
     * If id, name, password null then feedback exception error
     * If id less then 6 and password less then 8 then feedback exception error
     */
    public function validationUserRegistrationRequest(UserRegistrationRequest $request)
    {
        if (
            $request->id == null || $request->name == null || $request->password == null ||
            trim($request->id) == "" || trim($request->name) == "" || trim($request->password) == ""
        ) {
            throw new ValidationException("Id,name or password can't blank", 403);
        } else if (strlen($request->id) <= 6 || strlen($request->password) <= 8) {
            throw new ValidationException("Id can't less then 6 or Password can't less then 8", 403);
        }
    }
}
