<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC\App;

class Redirect
{
    public static function to(string $link)
    {
        header("Location: $link");
        exit();
    }
}
