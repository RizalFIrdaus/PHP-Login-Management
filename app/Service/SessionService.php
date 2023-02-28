<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Service;

use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\Session;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class SessionService
{
    public static string $key = "inisecret";
    public static string $COOKIE_NAME = "X-RIZAL-SESSION";
    public function __construct(
        private SessionRepository $sessionRepository,
        private UserRepository $userRepository
    ) {
    }

    public function create(string $user_id): Session
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = $user_id;
        $payload = [
            "session_id" => $session->id = uniqid(),
            "username" => $user_id
        ];
        $jwt = JWT::encode($payload, self::$key, "HS256");
        $session = $this->sessionRepository->save($session);
        setcookie(self::$COOKIE_NAME, $jwt, time() + (60 * 60 * 24 * 30), "/", httponly: true);
        return $session;
    }

    public function destroy(): void
    {
        $jwt = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $this->sessionRepository->deleteById($jwt->session_id);
        setcookie(self::$COOKIE_NAME, "", 1, "/");
    }

    public function current()
    {
        $jwt = $_COOKIE[self::$COOKIE_NAME] ?? '';
        if (!$jwt) {
            return null;
        }
        return JWT::decode($jwt, new Key(self::$key, "HS256"));
    }
}
