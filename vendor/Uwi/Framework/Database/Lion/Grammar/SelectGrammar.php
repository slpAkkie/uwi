<?php

namespace Uwi\Database\Lion\Grammar;

class SelectGrammar extends Grammar
{
    /**
     * Get query data according to Grammar class
     *
     * @return array
     */
    public function get(): array
    {
        $sql = "select {$this->query->getColumns()} from {$this->query->table}";

        for ($i = 0; $i < count($this->query->params); $i++) {
            if ($i === 0) {
                $sql .= " where {$this->query->params[$i][0]} {$this->query->params[$i][1]} ?";
            } else {
                $sql .= " {$this->query->params[$i][3]} {$this->query->params[$i][0]} {$this->query->params[$i][1]} ?";
            }
        }

        return [$sql, array_reduce($this->query->params, function ($carry, $item) {
            $carry[] = $item[2];

            return $carry;
        }, [])];
    }
}
