<?php

namespace App\Filament\Resources\DetailpenjualanResource\Pages;

use App\Filament\Resources\DetailpenjualanResource\Widgets\DetailpenjualanWidget;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\DetailpenjualanResource;

class CreateDetailpenjualan extends CreateRecord
{
    protected static string $resource = DetailpenjualanResource::class;

    protected function getFormActions(): array
    {
        return [
            Action::make('create')
                ->label(__('Simpan'))
                ->submit('create')
                ->keyBindings(['mod+s'])
        ];
    }
    protected function getRedirectUrl(): string
    {
        $id = $this->record->penjualan_id;
        return route(
            'filament.admin.resources.detailpenjualans.create',
            [
                'penjualan_id' => $id
            ]
        );
    }
    public function getFooterWidgetsColumns(): array|int|string{
        return 1;
    }
    public function getFooterWidgets(): array{
        return [
            DetailpenjualanWidget::make([
                'record' => request('penjualan_id')
            ]),
        ];
    }
}
