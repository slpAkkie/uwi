<?php

/**
 * Dump
 *
 * @param array $args
 * @return void
 */
function d(...$args): void
{
    foreach ($args as $arg) {
        echo '<pre style="margin: 10px; padding: 25px; background: #0d0d0e; color: #00cf2d; font-size: .8em; font-family: \'Fira Code\'">';
        var_dump($arg);
        echo '</pre>';
    }
}

/**
 * Dump and die
 *
 * @param array $args
 * @return void
 */
function dd(...$args): void
{
    d(...$args);

    exit(1);
}
