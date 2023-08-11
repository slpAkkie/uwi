<?php

namespace App\Http\Controllers;

use Uwi\Foundation\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function welcome()
    {
        return view('welcome');
    }
}
