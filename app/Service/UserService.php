<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Service;

use Exception;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;
use ProgrammerZamanNow\Belajar\PHP\MVC\Exception\ValidationException;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserLoginRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserLoginResponse;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserPasswordRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserPasswordResponse;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserProfileRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserProfileResponse;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserRegistrationRequest;
use ProgrammerZamanNow\Belajar\PHP\MVC\Model\UserRegistrationResponse;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;


class UserService
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
            Database::beginTrans();
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
            Database::commitTrans();
            return $response;
        } catch (\Exception $exception) {
            Database::rollbackTrans();
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

    public function login(UserLoginRequest $request): UserLoginResponse
    {
        $this->validationUserLoginRequest($request);
        $user = $this->userRepository->getById($request->id);
        if ($user == null) {
            throw new ValidationException("Id or password is wrong");
        }

        if (password_verify($request->password, $user->getPassword())) {
            $response = new UserLoginResponse();
            $response->user = $user;
            return $response;
        } else {
            throw new ValidationException("Id or password is wrong");
        }
    }

    public function validationUserLoginRequest(UserLoginRequest $request)
    {
        if (
            $request->id == null || $request->password == null ||
            trim($request->id) == "" || trim($request->password) == ""
        ) {
            throw new ValidationException("Id,name or password can't blank", 403);
        } else if (strlen($request->id) <= 6 || strlen($request->password) <= 8) {
            throw new ValidationException("Id can't less then 6 or Password can't less then 8", 403);
        }
    }

    public function updateProfile(UserProfileRequest $request): UserProfileResponse
    {
        $this->validationProfileRequest($request);
        try {
            Database::beginTrans();

            $user = $this->userRepository->getById($request->id);
            if ($user == null) {
                throw new ValidationException("User not found !");
            }
            $user->setName($request->name);
            $this->userRepository->update($user);
            $response = new UserProfileResponse();
            $response->user = $user;
            Database::commitTrans();

            return $response;
        } catch (ValidationException $exception) {
            Database::rollbackTrans();
            throw $exception;
        }
    }

    public function validationProfileRequest(UserProfileRequest $request)
    {
        if (
            $request->id == null || $request->name == null
            || trim($request->id) == "" || trim($request->name) == ""
        ) {
            throw new ValidationException("Name can't blank !");
        }
    }

    public function updatePassword(UserPasswordRequest $request): UserPasswordResponse
    {
        $this->validationPasswordRequest($request);
        try {
            Database::beginTrans();

            $user = $this->userRepository->getById($request->id);
            if ($user == null) {
                throw new ValidationException("User Not Found !");
            }
            if (password_verify($request->newPassword, $user->getPassword())) {
                throw new ValidationException("New password not be same !");
            }
            if (password_verify($request->oldPassword, $user->getPassword())) {
                $user->setPassword(password_hash($request->newPassword, PASSWORD_BCRYPT));
                $this->userRepository->updatePassword($user);
                $response = new UserPasswordResponse();
                $response->user = $user;
                Database::commitTrans();

                return $response;
            } else {
                throw new ValidationException("Old Password not match");
            }
        } catch (ValidationException $exception) {
            Database::rollbackTrans();
            throw $exception;
        }
    }

    public function validationPasswordRequest(UserPasswordRequest $request)
    {
        if (
            $request->oldPassword == null || $request->newPassword == null
            || trim($request->oldPassword) == "" || trim($request->newPassword) == ""
        ) {
            throw new ValidationException("Old or new password can't blank !");
        } else if (strlen($request->newPassword) <= 8) {
            throw new ValidationException("Password can't less then 8", 403);
        }
    }
}
