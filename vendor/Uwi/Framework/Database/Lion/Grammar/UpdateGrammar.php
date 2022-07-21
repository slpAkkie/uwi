<?php

namespace Uwi\Database\Lion\Grammar;

class UpdateGrammar extends Grammar
{
    /**
     * Get query data according to Grammar class
     *
     * @return array
     */
    public function get(): array
    {
        $values = [];
        $statements = [];
        foreach ($this->props['props'] as $column => $value) {
            $statements[] = "{$column}=?";
            $values[] = $value;
        }

        $statements = join(',', $statements);
        $values[] = $this->props['primaryKey'];
        return ["update {$this->query->table} set {$statements} where {$this->props['primaryKeyName']} = ?", $values];
    }
}
