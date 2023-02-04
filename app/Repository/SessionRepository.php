<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Repository;

use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\Session;


class SessionRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    public function save(?Session $session = null): ?Session
    {
        if ($session == null) return null;
        $statement = $this->connection->prepare("INSERT INTO sessions (id,user_id) VALUES (?,?)");
        $statement->execute([$session->id, $session->user_id]);
        return $session;
    }

    public function getById(?string $id = null): ?Session
    {
        if ($id == null) return null;
        $statement = $this->connection->prepare("SELECT id,user_id FROM sessions WHERE id=?");
        $statement->execute([$id]);
        try {
            if ($row = $statement->fetch()) {
                $session = new Session();
                $session->id = $row["id"];
                $session->user_id = $row["user_id"];
                return $session;
            } else {
                return null;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function deleteById(string $id = null): bool
    {
        if ($id == null) return false;
        $statement = $this->connection->prepare("SELECT id FROM sessions WHERE id=?");
        $statement->execute([$id]);
        try {
            if ($statement->fetch()) {
                $statement = $this->connection->prepare("DELETE FROM sessions WHERE id=?");
                $statement->execute([$id]);
                return true;
            } else {
                return false;
            }
        } finally {
            $statement->closeCursor();
        }
    }

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE FROM sessions");
    }
}
