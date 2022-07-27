<?php

namespace App\Http\Controllers;

use Uwi\Contracts\Application\ApplicationContract;
use Uwi\Services\Http\Controller as HttpController;
use Uwi\Services\Http\Request\Request;

class Controller extends HttpController
{
    public function welcome(ApplicationContract $app, Request $request)
    {
        return view('welcome', [
            'version' => '2.x-alpha',
        ])->statusCode(404);
    }
}
