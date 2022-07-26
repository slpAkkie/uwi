<?php

/**
 * Dump.
 *
 * @param array<mixed> $args
 * @return void
 */
function d(...$args): void
{
    static $cssInjected = false;

    foreach ($args as $arg) {
        $css = $cssInjected === true ? '' : <<<HTML
            <style>
                * { box-sizing: border-box; }
                body { margin: 0;
                    min-height: 100%;
                    background: #0d0d0e; }
                pre.dump-pre {
                    margin: 0;
                    padding: 25px;
                    color: #00cf2d;
                    font-size: .8em;
                    font-family: 'Fira Code';
                    word-wrap: break-word; }
                hr.dump-hr {
                    border: 2px dashed #00cf2d; }
            </style>
        HTML;
        $hr = $cssInjected ? <<<HTML
            <hr class="dump-hr" />
        HTML : '';
        $text = str_replace('    ', '  ', print_r($arg, true));

        if ($cssInjected === false) {
            $cssInjected = true;
        }

        echo <<<HTML
            $css
            $hr
            <pre class="dump-pre">$text</pre>
        HTML;
    }
}

/**
 * Dump and die.
 *
 * @param array<mixed> $args
 * @return void
 */
function dd(...$args): void
{
    d(...$args);

    exit(1);
}
