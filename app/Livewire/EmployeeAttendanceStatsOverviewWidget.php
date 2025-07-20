<?php

namespace App\Livewire;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EmployeeAttendanceStatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Departement Name', auth()->user()->employee->departement->departement_name),
            Stat::make('Max Clock In Time', auth()->user()->employee->departement->max_clock_in_time),
            Stat::make('Max Clock Out Time', auth()->user()->employee->departement->max_clock_out_time),
        ];
    }
}
