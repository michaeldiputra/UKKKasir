<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class detailpenjualan extends Model
{
    // Relasi ke Penjualan
    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class);
    }

    // Relasi ke Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    // Event saat model disimpan
    protected static function booted()
    {
        // Saat detail penjualan dibuat
        static::created(function ($detailpenjualan) {
            // 1. Kurangi stok produk
            $produk = Produk::find($detailpenjualan->produk_id);
            if ($produk) {
                $produk->stok -= $detailpenjualan->jumlah_produk;
                $produk->save();
            }

            // 2. Update total_harga di penjualan
            self::updateTotalPenjualan($detailpenjualan->penjualan_id);
        });

        // Saat detail penjualan diupdate
        static::updated(function ($detailpenjualan) {
            // Update total_harga di penjualan
            self::updateTotalPenjualan($detailpenjualan->penjualan_id);
        });

        // Saat detail penjualan dihapus
        static::deleted(function ($detailpenjualan) {
            // 1. Kembalikan stok produk
            $produk = Produk::find($detailpenjualan->produk_id);
            if ($produk) {
                $produk->stok += $detailpenjualan->jumlah_produk;
                $produk->save();
            }

            // 2. Update total_harga di penjualan
            self::updateTotalPenjualan($detailpenjualan->penjualan_id);
        });
    }

    // Fungsi untuk mengupdate total penjualan
    private static function updateTotalPenjualan($penjualan_id)
    {
        // Hitung total dari semua detail penjualan
        $total = self::where('penjualan_id', $penjualan_id)->sum('subtotal');

        // Update total_harga di tabel penjualan
        $penjualan = Penjualan::find($penjualan_id);
        if ($penjualan) {
            $penjualan->total_harga = $total;
            $penjualan->save();
        }
    }
}