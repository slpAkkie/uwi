<?php

/**
 * Dump
 *
 * @param array $args
 * @return void
 */
function d(...$args): void
{
    echo '<pre>';
    foreach ($args as $arg) {
        var_dump($arg);
        echo '<br><br>';
    }
    echo '</pre>';
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

    exit;
}
