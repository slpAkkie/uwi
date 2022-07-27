<?php

namespace Uwi\Services\Database\Lion\Contracts;

interface ModelContract
{
    /**
     * Get dirty columns and it's values.
     *
     * @return array<string, mixed>
     */
    public function getDirty(): array;

    /**
     * Indicate if there are not saved data in the model.
     *
     * @return boolean
     */
    public function isDirty(): bool;
}
