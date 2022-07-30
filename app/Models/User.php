<?php

namespace App\Models;

use Uwi\Services\Database\Lion\Model;

class User extends Model
{
    /**
     * Column allowed for mass-assignment.
     *
     * @var array
     */
    protected array $fillable = [
        'name',
    ];
}
