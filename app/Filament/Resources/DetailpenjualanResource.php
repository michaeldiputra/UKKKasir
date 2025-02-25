<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Set;
use Filament\Forms\Form;
use \App\Models\Penjualan;
use Filament\Tables\Table;
use App\Models\Detailpenjualan;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DetailpenjualanResource\Pages;
use App\Filament\Resources\DetailpenjualanResource\RelationManagers;

class DetailpenjualanResource extends Resource
{
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $model = Detailpenjualan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public $penjualanId;
    public function mount($record)
    {
        $this
            ->penjualanId = $record;
    }

    public static function form(Form $form): Form
    {
        $penjualan = new Penjualan();
        if (
            request()
                ->filled('penjualan_id')
        ) {
            $penjualan = Penjualan::find(request('penjualan_id'));
        }
        return $form

            ->schema([
                Forms\Components\DatePicker::make('tanggal')
                    ->required()
                    ->default($penjualan
                        ->tanggal)
                    ->columnSpanFull()
                    ->disabled(),
                TextInput::make('pelanggan_id')
                    ->required()
                    ->default($penjualan
                        ->pelanggan?->nama_pelanggan)
                    ->disabled(),
                TextInput::make('nomor_telepon')
                    ->required()
                    ->default($penjualan
                        ->pelanggan?->nomor_telepon)
                    ->disabled(),
                Select::make("produk_id")
                    ->required()
                    ->options(
                        \App\Models\Produk::pluck('nama_produk', 'id')
                    )
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $produk = \App\Models\Produk::find($state);
                        $set('harga', $produk->harga ?? null);

                        // Tambahkan informasi stok
                        $stokTersedia = $produk->stok ?? 0;
                        $set('info_stok', "Stok tersedia: $stokTersedia");

                        $harga = $produk->harga ?? 0;
                        $jumlahProduk = $get('jumlah_produk') ?? 1;
                        $set('subtotal', $harga * $jumlahProduk);
                    }),
                TextInput::make("harga")
                    ->required()
                    ->disabled(),
                TextInput::make("info_stok")
                    ->label('Informasi Stok')
                    ->disabled()
                    ->dehydrated(false),
                TextInput::make("jumlah_produk")
                    ->default('1')
                    ->required()
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        // Update subtotal when quantity changes
                        $harga = $get('harga') ?? 0;
                        $set('subtotal', $harga * $state);
                    }),
                TextInput::make("subtotal")
                    ->disabled()
                    ->dehydrated()  // Make sure this field is included when form is submitted
                    ->default(0),
                Hidden::make("penjualan_id")
                    ->default(request('penjualan_id')),
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
            'index' => Pages\ListDetailpenjualans::route('/'),
            'create' => Pages\CreateDetailpenjualan::route('/create'),
            'edit' => Pages\EditDetailpenjualan::route('/{record}/edit'),
        ];
    }
}
