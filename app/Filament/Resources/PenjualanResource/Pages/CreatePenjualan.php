<?php

namespace App\Filament\Resources\PenjualanResource\Pages;

use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\PenjualanResource;

class CreatePenjualan extends CreateRecord
{
    protected static string $resource = PenjualanResource::class;

    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label(__('Selanjutnya'))
                ->submit('create')
                ->keyBindings(['mod+s'])
        ];
    }
    protected function getRedirectUrl(): string
    {
        $id = $this->record->id;
        return route(
            'filament.admin.resources.detailpenjualans.create',
            [
                'detailpenjualan' => $id
            ]
        );
    }
}
