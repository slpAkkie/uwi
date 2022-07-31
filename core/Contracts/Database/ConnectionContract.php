<?php

namespace Uwi\Contracts\Database;

use Uwi\Contracts\Container\SingletonContract;

interface ConnectionContract extends SingletonContract
{
    /**
     * Returns ID of last insterted row.
     *
     * @return integer|null
     */
    public function lastInsertedId(): int|null;

    /**
     * Exec a query.
     *
     * @return array
     */
    public function exec(string $query, array $args = []): array;
}
