<?php

namespace App\Http\Controllers;

use Uwi\Services\Http\Controller as HttpController;

class Controller extends HttpController
{
    public function welcome()
    {
        dd('Hello World!!!');
    }
}
