<?php

namespace App\Filament\Resources\DetailpenjualanResource\Pages;

use App\Filament\Resources\DetailpenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditDetailpenjualan extends EditRecord
{
    protected static string $resource = DetailpenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
