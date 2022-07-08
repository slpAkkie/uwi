<?php

namespace App\Http\Controllers;

use App\Models\User;
use Uwi\Foundation\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function welcome()
    {
        dd(User::raw('select * from users where id = ?', [1]));
        return view('welcome');
    }
}
