<?php

namespace App\Filament\Pages;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class EmployeProfile extends Page implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];

    public static string $view = 'filament.pages.employee-profile';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public function mount()
    {
        $data = DB::table('employees')
            ->join('users', 'employees.user_id', 'users.id')
            ->join('departements', 'departements.id', 'employees.departement_id')
            ->where('employees.id', auth()->user()->employee->id)
            ->get([
                'employees.id',
                'employees.employee_id',
                'employees.departement_id',
                'employees.name',
                'employees.address',
                'employees.created_at',
                'users.id',
                'users.email',
                'departements.id',
                'departements.departement_name'
            ])->map(function ($item) {
                return (array) $item;
            });

        $this->form->fill($data[0]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        Section::make("Profile")
                            ->schema([
                                TextInput::make("employee_id")
                                    ->readOnly(),
                                TextInput::make("departement_name")
                                    ->readOnly(),
                                TextInput::make("name")
                                    ->readOnly(),
                                TextInput::make("email")
                                    ->readOnly(),
                                TextInput::make("address")
                                    ->readOnly(),
                                TextInput::make("created_at")
                                    ->readOnly()
                                    ->label("Registered At"),
                            ])->columns([
                                'default' => 1,
                                'sm' => 2
                            ]),
                    ])
            ])->statePath('data');
    }

    public static function canAccess(): bool
    {
        return auth()->user()->hasRole('employee');
    }
}
