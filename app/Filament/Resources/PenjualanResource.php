<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Penjualan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PenjualanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PenjualanResource\RelationManagers;

class PenjualanResource extends Resource
{
    protected static ?string $model = Penjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('tanggal')->required()->default(now())->columnSpanFull(),
                Forms\Components\Select::make('pelanggan_id')->required()->searchable()
                    ->options(
                        \App\Models\Pelanggan::pluck('nama_pelanggan', 'id')
                    )->createOptionForm(
                        \App\Filament\Resources\PelangganResource::getForm(),
                    )->createOptionUsing(function (array $data): int {
                        return \App\Models\Pelanggan::create($data)->id;
                    })->reactive()
                    ->afterStateUpdated(function ($state, Set $set){
                        $set('nomor_telepon', \App\Models\Pelanggan::find($state)->nomor_telepon);
                    })
                    ,
                Forms\Components\TextInput::make('nomor_telepon')->required()->disabled(),
                Forms\Components\Hidden::make('total_harga')->default('0'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPenjualans::route('/'),
            'create' => Pages\CreatePenjualan::route('/create'),
            'edit' => Pages\EditPenjualan::route('/{record}/edit'),
        ];
    }
}
