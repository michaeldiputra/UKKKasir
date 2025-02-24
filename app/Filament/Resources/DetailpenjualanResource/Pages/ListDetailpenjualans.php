<?php

namespace App\Filament\Resources\DetailpenjualanResource\Pages;

use App\Filament\Resources\DetailpenjualanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDetailpenjualans extends ListRecords
{
    protected static string $resource = DetailpenjualanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
