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
}
