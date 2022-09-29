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
            'version' => env('APP_VERSION'),
        ]);
    }

    /**
     * Show page for testing Calibri.
     *
     * @return ResponseContract
     */
    public function calibri(): ResponseContract
    {
        return view('calibri', [
            'appName' => env('APP_NAME'),
        ]);
    }

    /**
     * Show page about webpack tools.
     *
     * @return ResponseContract
     */
    public function webpack(): ResponseContract
    {
        return view('webpack');
    }
}
