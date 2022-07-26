<?php

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
                font-family: 'Fira Code';
                white-space: pre-wrap;
                background: #0d0d0e; }
        </style>
        HTML;
    }

    echo '<pre class="dump-pre">';
    foreach ($args as $arg) {
        echo '>>> ';
        var_dump($arg);
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
    d(...$args);

    exit(1);
}

/**
 * Primary exception handler.
 * Just print info from Exception object
 *
 * @param Throwable $e
 * @return void
 */
function ddException(Throwable $e): void
{
    $stackTraceDepth = 0;

    dd(
        join('<br />', array_merge([
            <<<HTML
            Message: {$e->getMessage()}
            Exit code: {$e->getCode()}
            HTML, '', '', '',


            'Stack Trace:', '',

            "#$stackTraceDepth {$e->getFile()}({$e->getLine()})"
        ], array_map(function ($el) use (&$stackTraceDepth) {
            extract($el);
            $el = '#' . ++$stackTraceDepth . ' ';

            $el .= isset($file, $line) ? "$file($line): " : "[Internal code]: ";

            $args = isset($args)
                ? ($args = strlen($args = join(', ', $args)) > 16 ? '...' : $args)
                : '';

            $el .= isset($class) ? "$class$type$function($args)" : "$function(...)";

            return $el;
        }, $e->getTrace())))
    );
}
