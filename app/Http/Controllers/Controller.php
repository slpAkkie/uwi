<?php

namespace App\Http\Controllers;

use App\Models\User;
use Uwi\Foundation\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function welcome()
    {
        $user = User::find(2);

        $user->delete();

        dd($user);

        return view('welcome');
    }
}
