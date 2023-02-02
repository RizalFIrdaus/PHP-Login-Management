<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\Domain;

class User
{
    private string $id;
    private string $name;
    private string $password;

    // SETTER AND GETTER
    public function getId(): string
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function setId(string $id): void
    {
        $this->id = $id;
    }
    public function setName(string $name): void
    {
        $this->name = $name;
    }
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
