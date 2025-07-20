<?php

namespace App\Filament\Resources\AttendanceHistoryResource\Pages;

use App\Filament\Resources\AttendanceHistoryResource;
use App\Models\AttendanceHistory;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAttendanceHistory extends ViewRecord
{
    protected static string $resource = AttendanceHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $employee = AttendanceHistory::with(['attendance', 'employee', 'employee.departement'])
            ->where('id', $this->record->id)
            ->firstOrFail();

        $data['attendance_type'] =  $data['attendance_type'] == 1 ? 'In' : 'Out';
        $data['departement_name'] = $employee->employee->departement->departement_name;
        $data['clock_in'] = $employee->attendance->clock_in;
        $data['clock_out'] = $employee->attendance->clock_out;

        return $data;
    }
}
