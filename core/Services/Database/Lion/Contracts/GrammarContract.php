<?php

namespace Uwi\Services\Database\Lion\Contracts;

interface GrammarContract
{
    /**
     * Get query data according to Grammar class.
     *
     * @return array
     */
    public function get(): array;
}
