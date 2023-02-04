<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Service;

use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\Session;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\UserRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;


class SessionService
{
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
        $session = $this->sessionRepository->save($session);
        setcookie(self::$COOKIE_NAME, $session->id, time() + (60 * 60 * 24 * 30), "/");
        return $session;
    }

    public function destroy(): void
    {
        $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $this->sessionRepository->deleteById($sessionId);
        setcookie(self::$COOKIE_NAME, "", 1, "/");
    }

    public function current(): ?User
    {
        $sessionId = $_COOKIE[self::$COOKIE_NAME] ?? '';
        $session = $this->sessionRepository->getById($sessionId);
        if ($session == null) {
            return null;
        }
        return $this->userRepository->getById($session->user_id);
    }
}
