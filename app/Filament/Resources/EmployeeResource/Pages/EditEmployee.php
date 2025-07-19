<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave()
    {
        DB::transaction(function () {
            $formData = $this->data;

            User::where('id', $formData['user_id'])
                ->update([
                    'name' => $formData['name'],
                    'email' => $formData['email'],
                    'password' => bcrypt($formData['password'])
                ]);
        });
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = DB::table('users')->where('id', $data['user_id'])->firstOrFail(['id', 'email', 'password']);

        $data['email'] = $user->email;

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = DB::table('users')->where('id', $data['user_id'])->firstOrFail(['id', 'email', 'password']);

        $data['password'] = $data['password'] ?? $user->password;

        return $data;
    }
}
