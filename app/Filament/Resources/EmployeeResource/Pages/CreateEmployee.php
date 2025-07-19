<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function afterValidate(): void
    {
        DB::transaction(function () {
            $formData = $this->data;

            $user = User::create([
                'name' => $formData['name'],
                'email' => $formData['email'],
                'password' => bcrypt($formData['password'])
            ]);

            $user->assignRole('employee');
        });
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $userId = DB::table('users')->where('email', $data['email'])->value('id');

        $data['user_id'] = $userId;

        return $data;
    }
}
