<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Repository;

use PDOException;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;

class UserRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    // Save user into database
    public function save(User $user): ?User
    {
        $id = $user->getId();
        $name = $user->getName();
        $password = $user->getPassword();
        if ($id == null || $name == null || $password == null) return null;
        try {
            $statement = $this->connection->prepare("INSERT INTO users(id,name,password) VALUES (?,?,?)");
            $statement->execute([
                $user->getId(),
                $user->getName(),
                $user->getPassword()
            ]);
            return $user;
        } catch (\PDOException $exception) {
            throw $exception->getMessage();
        }
    }

    public function getById(string $id): ?User
    {
        $statement = $this->connection->prepare("SELECT id,name,password FROM users WHERE id=?");
        $statement->execute([$id]);

        // Trying to fetch data from id
        try {
            if ($row = $statement->fetch()) {
                $user = new User();
                $user->setId($row["id"]);
                $user->setName($row["name"]);
                $user->setPassword($row["password"]);
                return $user;
            } else {
                return null;
            }
        } catch (\PDOException $exception) {
            throw $exception->getMessage();
        } finally {
            // Close Query
            $statement->closeCursor();
        }
    }

    public function update(?User $user): ?User
    {
        if ($user == null) return null;
        $statement = $this->connection->prepare("UPDATE users SET name=? WHERE id=?");
        $statement->execute([$user->getName(), $user->getId()]);
        return $user;
    }

    public function updatePassword(?User $user): ?User
    {
        if ($user == null) return null;
        $statement = $this->connection->prepare("UPDATE users SET password=? WHERE id=?");
        $statement->execute([$user->getPassword(), $user->getId()]);
        return $user;
    }

    public function deleteAll(): void
    {
        $this->connection->exec("DELETE FROM users");
    }
}
