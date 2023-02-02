<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\App;

class View
{

    public static function render(string $view, $model)
    {
        require __DIR__ . "/../View/Layouts/header.php";
        require __DIR__ . '/../View/' . $view . '.php';
        require __DIR__ . "/../View/Layouts/footer.php";
    }
}
