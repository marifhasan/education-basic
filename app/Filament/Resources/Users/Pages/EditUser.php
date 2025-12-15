<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->action(function () {
                    // Prevent deleting super_admin users
                    if (!$this->record->hasRole('super_admin')) {
                        $this->record->delete();
                        $this->redirect($this->getResource()::getUrl('index'));
                    }
                })
                ->disabled(fn () => $this->record->hasRole('super_admin'))
                ->tooltip(fn () => $this->record->hasRole('super_admin') ? 'Cannot delete super admin users' : null),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
