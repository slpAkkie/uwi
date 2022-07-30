<?php

namespace Uwi\Services\Database\Lion\Grammar;

use Uwi\Services\Database\Lion\Contracts\GrammarContract;
use Uwi\Services\Database\Lion\Contracts\QueryContract;

abstract class Grammar implements GrammarContract
{
    /**
     * Instantiate Grammar.
     *
     * @param QueryContract $query
     */
    public function __construct(
        protected QueryContract $query,
        protected array $props,
    ) {
        //
    }
}
