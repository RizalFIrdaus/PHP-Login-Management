<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Repository;

use PDO;
use ProgrammerZamanNow\Belajar\PHP\MVC\Domain\User;

class UserRepository
{
    private \PDO $connection;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
    }

    // Save user into database
    public function save(User $user): User
    {
        $statement = $this->connection->prepare("INSERT INTO users(id,name,password) VALUES (?,?,?)");
        $statement->execute([
            $user->getId(),
            $user->getName(),
            $user->getPassword()
        ]);
        return $user;
    }

    public function getById(User $user): ?User
    {
        $statement = $this->connection->prepare("SELECT id,name,password FROM users WHERE id=?");
        $statement->execute([$user->getId()]);

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
        } finally {
            // Close Query
            $statement->closeCursor();
        }
    }
}
