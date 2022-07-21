<?php

namespace App\Http\Controllers;

use App\Models\User;
use Uwi\Foundation\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function welcome()
    {
        $user = new User([
            'name' => 'John Doe',
        ]);

        $user->save();

        return view('welcome');
    }
}
