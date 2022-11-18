<?php

namespace App\Http\Controllers;

use Services\Calibri\View;

class Controller
{
    public function welcome(): void
    {
        print(new View('welcome'));
    }
}
