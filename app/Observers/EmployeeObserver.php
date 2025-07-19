<?php

namespace App\Observers;

use App\Models\Employee;
use App\Models\User;
use Spatie\Permission\Models\Role;

class EmployeeObserver
{
    /**
     * Handle the Employee "deleted" event.
     */
    public function deleted(Employee $employee): void
    {
        User::where('id', $employee['user_id'])->delete();
    }
}
