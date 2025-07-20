<?php

namespace App\Filament\Pages;

use App\Livewire\EmployeeAttendanceStatsOverviewWidget;
use App\Models\Attendance;
use App\Models\AttendanceHistory;
use App\Models\Employee;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EmployeeAttendance extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.employee-attendance';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                AttendanceHistory::query()->with(['employee', 'attendance'])
                    ->where('attendance_histories.employee_id', auth()->user()->employee->employee_id)
            )
            ->columns([
                TextColumn::make('id')
                    ->rowIndex()
                    ->label('No'),
                TextColumn::make('date_attendance')
                    ->label('Date Attendance'),
                TextColumn::make('attendance_type')
                    ->label('Attendance Type')
                    ->formatStateUsing(fn(string $state) => $state == 1 ? "In" : "Out"),
                TextColumn::make('attendance.clock_in')
                    ->label('Attendance In')
                    ->formatStateUsing(fn(Model $record, string $state) => $record->attendance_type == 1 ? $state : "--"),
                TextColumn::make('attendance.clock_out')
                    ->label('Attendance Out')
                    ->default('Not Yet')
                    ->formatStateUsing(fn(Model $record, string $state) => $record->attendance_type == 2 ? $state : "--"),
                TextColumn::make('description')
                    ->label('Description'),
            ])
            ->filters([
                // ...
            ])
            ->actions([
                // ...
            ])
            ->bulkActions([
                // ...
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('attendance_in')
                ->color('success')
                ->icon('heroicon-m-arrow-right-end-on-rectangle')
                ->action(function () {
                    // Get Employee
                    $employee = auth()->user()->employee;

                    // Get max clock in time
                    $maxClockInTime = $employee->departement->max_clock_in_time;

                    // Get attendance histories
                    $isAttendanceToday = DB::table('attendance_histories')
                        ->where('employee_id', $employee->id)
                        ->whereDate('date_attendance', date("Y-m-d"))
                        ->exists();

                    // Check if already attendance today
                    if ($isAttendanceToday) {
                        Notification::make()
                            ->warning()
                            ->title("Anda sudah absen masuk hari ini.")
                            ->send();
                        return;
                    }

                    // Check if now is greater than $maxClockInTime
                    if (now()->greaterThanOrEqualTo($maxClockInTime)) {
                        Notification::make()
                            ->warning()
                            ->title("Anda sudah terlambat untuk absen masuk.")
                            ->send();
                        return;
                    }

                    // Check if now is less than $maxClockInTime > 1
                    if (now()->diffInHours($maxClockInTime, false) > 1) {
                        Notification::make()
                            ->warning()
                            ->title("Absen dibuka saat 1 jam sebelum masuk.")
                            ->send();
                        return;
                    }

                    DB::transaction(function () use ($employee) {
                        $attendance = Attendance::create([
                            'attendance_id' => Str::upper(Str::random(5)) . "-" . random_int(100000, 999999),
                            'clock_in' => now(),
                            'clock_out' => null,
                            'employee_id' => $employee->id
                        ]);

                        AttendanceHistory::create([
                            'attendance_id' => $attendance->attendance_id,
                            'attendance_type' => 1,
                            'date_attendance' => now(),
                            'description' => "Absen masuk dengan waktu " . now()->diffForHumans($employee->departement->max_clock_in_time),
                            'employee_id' => $employee->id
                        ]);

                        Notification::make()
                            ->success()
                            ->title("Absen masuk berhasil.")
                            ->send();
                    });
                }),
            Action::make('attendance_out')
                ->icon('heroicon-m-arrow-right-start-on-rectangle')
                ->action(function () {
                    // Get Employee
                    $employee = auth()->user()->employee;

                    // Get max clock out time
                    $maxClockOutTime = $employee->departement->max_clock_out_time;

                    // Get attendance histories
                    $attendance = DB::table('attendance_histories')
                        ->join('attendances', 'attendance_histories.attendance_id', 'attendances.attendance_id')
                        ->where('attendance_histories.employee_id', $employee->id)
                        ->whereDate('attendance_histories.date_attendance', date("Y-m-d"))
                        ->where('attendances.clock_out', null);

                    $attendanceIn = DB::table('attendance_histories')
                        ->join('attendances', 'attendance_histories.attendance_id', 'attendances.attendance_id')
                        ->where('attendance_histories.employee_id', $employee->id)
                        ->whereDate('attendance_histories.date_attendance', date("Y-m-d"))
                        ->where('attendances.clock_in', null)
                        ->exists();

                    $attendanceOut = DB::table('attendance_histories')
                        ->join('attendances', 'attendance_histories.attendance_id', 'attendances.attendance_id')
                        ->where('attendance_histories.employee_id', $employee->id)
                        ->whereDate('attendance_histories.date_attendance', date("Y-m-d"))
                        ->where('attendances.clock_out', "!=", null)
                        ->exists();

                    // Check if already attendance in today
                    if ($attendanceIn) {
                        Notification::make()
                            ->warning()
                            ->title("Anda belum absen masuk hari ini.")
                            ->send();
                        return;
                    }

                    // Check if already attendance out today
                    if ($attendanceOut) {
                        Notification::make()
                            ->warning()
                            ->title("Anda sudah absen pulang hari ini.")
                            ->send();
                        return;
                    }

                    // Check if now is greater than $maxClockOutTime
                    if (now()->greaterThanOrEqualTo($maxClockOutTime)) {
                        Notification::make()
                            ->warning()
                            ->title("Anda sudah terlambat untuk absen pulang.")
                            ->send();
                        return;
                    }

                    // Check if now is less than $maxClockOutTime > 1
                    if (now()->diffInHours($maxClockOutTime, false) > 1) {
                        Notification::make()
                            ->warning()
                            ->title("Absen dibuka saat 1 jam sebelum pulang.")
                            ->send();
                        return;
                    }

                    DB::transaction(function () use ($employee, $attendance) {
                        $attendanceId = $attendance->first(['attendance_histories.attendance_id']);

                        $updatedAttendance = Attendance::query()
                            ->where('employee_id', $employee->id)
                            ->where('attendance_id', $attendanceId->attendance_id)
                            ->update([
                                'clock_out' => now(),
                            ]);

                        AttendanceHistory::create([
                            'attendance_id' => $attendanceId->attendance_id,
                            'attendance_type' => 2,
                            'date_attendance' => now(),
                            'description' => "Absen pulang dengan waktu " . now()->diffForHumans($employee->departement->max_clock_out_time),
                            'employee_id' => $employee->id
                        ]);

                        Notification::make()
                            ->success()
                            ->title("Absen pulang berhasil.")
                            ->send();
                    });
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            EmployeeAttendanceStatsOverviewWidget::class
        ];
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('employee');
    }
}
