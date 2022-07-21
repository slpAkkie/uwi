<?php

namespace App\Http\Controllers;

use App\Models\User;
use Uwi\Foundation\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function welcome()
    {
        dd(User::where('id', 1)->orWhere('id', '>', 2)->dd());
        return view('welcome');
    }
}
