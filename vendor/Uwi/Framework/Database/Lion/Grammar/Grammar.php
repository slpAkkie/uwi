<?php

namespace Uwi\Database\Lion\Grammar;

use Uwi\Database\Lion\Query\Query;

abstract class Grammar
{
    /**
     * Query object to use for build query string
     *
     * @var Query
     */
    protected Query $query;

    /**
     * Props from query
     *
     * @var array
     */
    protected array $props;

    /**
     * Instantiate Grammar
     *
     * @param Query $query
     */
    public function __construct(Query $query, array $props)
    {
        $this->query = $query;
        $this->props = $props;
    }

    /**
     * Get query data according to Grammar class
     *
     * @return array
     */
    abstract public function get(): array;
}
