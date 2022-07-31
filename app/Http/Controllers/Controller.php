<?php

namespace App\Http\Controllers;

use Uwi\Contracts\Http\Response\ResponseContract;
use Uwi\Services\Http\Controller as HttpController;

class Controller extends HttpController
{
    /**
     * Show welcome page.
     *
     * @return ResponseContract
     */
    public function welcome(): ResponseContract
    {
        return view('welcome', [
            'version' => '2.x-alpha',
        ]);
    }
}
