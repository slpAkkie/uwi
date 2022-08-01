<?php

use Uwi\Contracts\Http\Response\ResponseContract;

/**
 * Dump.
 *
 * @param array<mixed> $args
 * @return void
 */
function d(mixed ...$args): void
{
    static $cssInjected = false;

    if ($cssInjected === false) {
        echo <<<HTML
        <style>
            * { box-sizing: border-box; }
            pre.dump-pre {
                margin: 0 0 10px 0;
                padding: 25px;
                color: #00cf2d;
                font-size: .8em;
                font-family: 'Consolas', sans-serif;
                white-space: pre-wrap;
                background: #0d0d0e; }
        </style>
        HTML;
    }

    echo '<pre class="dump-pre">';
    foreach ($args as $arg) {
        echo '>>> ';
        var_dump(is_string($arg) ? htmlspecialchars($arg) : $arg);
        echo '<br />';
    }
    echo '</pre>';

    $cssInjected = true;
}

/**
 * Dump and die.
 *
 * @param array<mixed> $args
 * @return void
 */
function dd(mixed ...$args): void
{
    ob_start();
    d(...$args);

    app()->make(ResponseContract::class, ob_get_clean(), 500)->send();
    exit(1);
}

/**
 * Primary exception handler.
 * Just print info from Exception object on page
 * and set status code to 500.
 *
 * @param Throwable $e
 * @return void
 */
function ddException(Throwable $e): void
{
    http_response_code(500);

    echo <<<HTML
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <!-- Meta -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        
        <!-- Style -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

        <!-- Title -->
        <title>Exception occured</title>
    </head>

    <body class="bg-light">
        <div class="container-fluid">
            <div class="row">
                <h1 class="m-0 py-4 p-5 bg-dark text-white"><span class="text-danger">Exception:</span> <span class="fs-4">{$e->getMessage()}</span></h1>

                <article class="p-5">
                    <div><b>In File:</b> {$e->getFile()}</div>
                    <div><b>On Line:</b> {$e->getLine()}</div>
                    <div><b>Exit Code:</b> {$e->getCode()}</div>
                </article>
            </div>
            <div class="row">
                <h2 class="m-0 py-4 px-5 bg-secondary text-white">Stack Trace</h2>

                <code class="py-4 px-5">
                    <pre>{$e->getTraceAsString()}</pre>
                </code>
            </div>
        </div>
    </body>

    </html>
    HTML;
}
