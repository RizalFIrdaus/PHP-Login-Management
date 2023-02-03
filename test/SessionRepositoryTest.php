<?php


namespace ProgrammerZamanNow\Belajar\PHP\MVC;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\Config\Database;
use ProgrammerZamanNow\Belajar\PHP\MVC\Repository\SessionRepository;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\Session;

class SessionRepositoryTest extends TestCase
{
    private SessionRepository $sessionRepository;

    public function setUp(): void
    {
        $this->sessionRepository = new SessionRepository(Database::getConnection());
        $this->sessionRepository->deleteAll();
    }

    public function testSave()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "rizal300500";
        $result = $this->sessionRepository->save($session);

        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->user_id, $result->user_id);
    }

    public function testGetByIdSuccess()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "rizal300500";
        $this->sessionRepository->save($session);
        $result = $this->sessionRepository->getById($session->id);
        self::assertEquals($session->id, $result->id);
        self::assertEquals($session->user_id, $result->user_id);
    }

    public function testGetByIdFailed()
    {
        $result = $this->sessionRepository->getById("Salah");
        self::assertNull($result);
    }

    public function testDeleteByIdSuccess()
    {
        $session = new Session();
        $session->id = uniqid();
        $session->user_id = "rizal300500";
        $this->sessionRepository->save($session);
        $result = $this->sessionRepository->deleteById($session->id);
        self::assertTrue($result);
    }

    public function testDeleteByIdFailed()
    {
        $result = $this->sessionRepository->deleteById("salah");
        self::assertFalse($result);
    }
}
