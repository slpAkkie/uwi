<?php

namespace App\Http\Controllers;

use Uwi\Services\Http\Controller as HttpController;
use Uwi\Services\Http\Request\Request;

class Controller extends HttpController
{
    public function welcome(Request $request)
    {
        return response('Hiiii. It\'s Uwi. Page only for testing in in dev mode', 404);
    }
}
