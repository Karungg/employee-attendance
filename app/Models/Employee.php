<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $primaryKey = 'id';
    protected $fillable = [
        'employee_id',
        'name',
        'address',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function departement(): BelongsTo
    {
        return $this->belongsTo(Departement::class, 'departement_id', 'id');
    }
}
