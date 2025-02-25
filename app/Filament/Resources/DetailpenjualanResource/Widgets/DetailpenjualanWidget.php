<?php

namespace App\Filament\Resources\DetailpenjualanResource\Widgets;

use Filament\Forms\Components\TextInput;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\Summarizers\Summarizer;

class DetailpenjualanWidget extends BaseWidget
{
    public $penjualanId;
    public function mount($record)
    {
        $this->penjualanId = $record;
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(
                \App\Models\Detailpenjualan::query()->where('penjualan_id', $this->penjualanId),
            )
            ->columns([
                Tables\Columns\TextColumn::make('produk.nama_produk')
                    ->label('Nama Produk'),
                Tables\Columns\TextColumn::make('jumlah_produk'),
                Tables\Columns\TextColumn::make('produk.harga')
                    ->label('Harga')
                    ->money('IDR'),
                Tables\Columns\TextColumn::make('subtotal')
                    ->money('IDR')
                    ->summarize(
                        Summarizer::make()
                            ->using(
                                function ($query) {
                                    return $query->sum(DB::raw('subtotal'));
                                }
                            )
                            ->money('IDR')
                    ),
            ])
            ->actions([
                Tables\Actions\DeleteAction::make()
            ])
        ;
    }
}
