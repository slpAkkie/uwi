<?php

namespace App\Http\Controllers;

use Uwi\Foundation\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function welcome()
    {
        return response('Hello world! I wrote a PHP MVC Framework');
    }
}
