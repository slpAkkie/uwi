<?php

namespace Uwi\Database\Lion\Grammar;

class InsertGrammar extends Grammar
{
    /**
     * Get query data according to Grammar class
     *
     * @return array
     */
    public function get(): array
    {
        $columns = [];
        $placeholders = [];
        $values = [];
        foreach ($this->props as $column => $value) {
            $columns[] = $column;
            $values[] = $value;
            $placeholders[] = '?';
        }
        $columns = join(',', $columns);
        $placeholders = join(',', $placeholders);
        return ["insert into {$this->query->table} ({$columns}) values ({$placeholders})", $values];
    }
}
