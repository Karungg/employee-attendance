<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AttendanceHistoryResource\Pages;
use App\Filament\Resources\AttendanceHistoryResource\RelationManagers;
use App\Models\AttendanceHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttendanceHistoryResource extends Resource
{
    protected static ?string $model = AttendanceHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DateTimePicker::make('date_attendance')
                    ->required(),
                Forms\Components\TextInput::make('attendance_type')
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Select::make('attendance_id')
                    ->relationship('attendance', 'id')
                    ->required(),
                Forms\Components\Select::make('employee_id')
                    ->relationship('employee', 'name')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->sortable()
                    ->rowIndex()
                    ->label('No'),
                Tables\Columns\TextColumn::make('attendance.attendance_id')
                    ->searchable()
                    ->label('Attendance ID'),
                Tables\Columns\TextColumn::make('date_attendance')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('attendance_type')
                    ->sortable()
                    ->formatStateUsing(fn(string $state) => $state == 1 ? "In" : "Out"),
                Tables\Columns\TextColumn::make('employee.name')
                    ->searchable()
                    ->label("Employee Name"),
                Tables\Columns\TextColumn::make('employee.departement.departement_name')
                    ->searchable()
                    ->label("Departement")
                    ->sortable(),
                Tables\Columns\TextColumn::make('attendance.clock_in')
                    ->searchable()
                    ->sortable()
                    ->label("Clock In")
                    ->formatStateUsing(fn(Model $record, string $state) => $record->attendance_type == 1 ? $state : "--"),
                Tables\Columns\TextColumn::make('attendance.clock_out')
                    ->searchable()
                    ->sortable()
                    ->label("Clock Out")
                    ->formatStateUsing(fn(Model $record, string $state) => $record->attendance_type == 2 ? $state : "--"),
                Tables\Columns\TextColumn::make('description')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canViewAny(): bool
    {
        return auth()->user()->hasRole('admin');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAttendanceHistories::route('/'),
            'view' => Pages\ViewAttendanceHistory::route('/{record}'),
        ];
    }
}
