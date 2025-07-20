<?php

namespace App\Filament\Resources\AttendanceHistoryResource\Pages;

use App\Filament\Resources\AttendanceHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAttendanceHistory extends EditRecord
{
    protected static string $resource = AttendanceHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
