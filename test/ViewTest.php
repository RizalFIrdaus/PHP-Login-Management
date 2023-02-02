<?php

namespace ProgrammerZamanNow\Belajar\PHP\MVC;

use PHPUnit\Framework\TestCase;
use ProgrammerZamanNow\Belajar\PHP\MVC\App\View;

class ViewTest extends TestCase
{
    public function testRender()
    {
        View::render("Home/index", [
            "title" => "Login"
        ]);
        self::expectOutputRegex("[Login]");
        self::expectOutputRegex("[Register]");
    }
}
