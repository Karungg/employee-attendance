<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departement extends Model
{
    protected $fillable = [
        'departement_name',
        'max_clock_in_time',
        'max_clock_out_time'
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'departement_id', 'id');
    }
}
