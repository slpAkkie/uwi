<?php

namespace Uwi\Services\Database\Lion\Grammar;

class SelectGrammar extends Grammar
{
    /**
     * Get query data according to Grammar class.
     *
     * @return array
     */
    public function get(): array
    {
        $wheres = $this->query->wheres;
        $conditions = [];

        foreach ($wheres as $i => $where) {
            if ($i === 0) {
                $conditions[] = "{$where[0]} {$where[1]} ?";
            } else {
                $conditions[] = "{$where[3]} {$where[0]} {$where[1]} ?";
            }
        }

        $conditions = count($conditions) ? 'where ' . join(' ', $conditions) : '';
        return ["select {$this->query->getColumns()} from {$this->query->table} {$conditions}", array_reduce($wheres, function ($carry, $item) {
            $carry[] = $item[2];

            return $carry;
        }, [])];
    }
}
