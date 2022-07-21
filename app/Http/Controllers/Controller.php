<?php

namespace App\Http\Controllers;

use App\Models\User;
use Uwi\Foundation\Http\Controllers\Controller as BaseController;

class Controller extends BaseController
{
    public function welcome()
    {
        $user = User::find(1);
        $user->name = 'asdasdasd';

        // $user->save();
        $user->update([
            'name' => 'Martin Madraso'
        ]);

        return view('welcome');
    }
}
