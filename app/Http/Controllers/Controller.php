<?php

namespace App\Http\Controllers;

use Uwi\Contracts\Http\Response\ResponseContract;
use Uwi\Services\Http\Controller as HttpController;

class Controller extends HttpController
{
    /**
     * Show page for Uwi.
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
     * Show page for Calibri.
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
     * Show page for Lion.
     *
     * @return ResponseContract
     */
    public function lion(): ResponseContract
    {
        return view('lion');
    }
}
