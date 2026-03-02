<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promosi extends Model
{
    protected $table = 'promosis';

    protected $fillable = [
        'judul', 'subjudul', 'deskripsi', 'manfaat', 'definisi',
        'bonus', 'gambar', 'harga_asli', 'harga_promo',
        'cta_label', 'cta_url', 'berlaku_hingga', 'is_active', 'urutan',
    ];

    protected $casts = [
        'berlaku_hingga' => 'date',
        'is_active'      => 'boolean',
    ];

    /**
     * Auto nonaktifkan promosi expired setiap kali model digunakan.
     * Berjalan sekali per request menggunakan flag static.
     */
    protected static function booted(): void
    {
        static $sudahDijalankan = false;

        if (!$sudahDijalankan) {
            $sudahDijalankan = true;

            static::where('is_active', true)
                ->whereNotNull('berlaku_hingga')
                ->whereDate('berlaku_hingga', '<', now()->toDateString())
                ->update(['is_active' => false]);
        }
    }

    /**
     * Accessor: selalu kembalikan manfaat sebagai array.
     */
    public function getManfaatAttribute($value): array
    {
        if (is_null($value)) return [];

        $decoded = $value;
        for ($i = 0; $i < 3; $i++) {
            if (is_array($decoded)) break;
            $decoded = json_decode($decoded, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return [];
            }
        }

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * Mutator: simpan manfaat sebagai JSON array satu level.
     */
    public function setManfaatAttribute($value): void
    {
        if (is_array($value)) {
            $this->attributes['manfaat'] = json_encode($value);
        } else {
            $this->attributes['manfaat'] = $value;
        }
    }

    public function scopeAktif($query)
    {
        return $query
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('berlaku_hingga')
                  ->orWhere('berlaku_hingga', '>=', now()->toDateString());
            })
            ->orderBy('urutan')
            ->orderBy('created_at', 'desc');
    }

    public function hargaPromoFormatted(): string
    {
        return 'Rp ' . number_format($this->harga_promo, 0, ',', '.');
    }

    public function hargaAsliFormatted(): ?string
    {
        return $this->harga_asli
            ? 'Rp ' . number_format($this->harga_asli, 0, ',', '.')
            : null;
    }

    public function isExpired(): bool
    {
        return $this->berlaku_hingga && $this->berlaku_hingga->isPast();
    }

    /**
     * URL gambar — disimpan di public_html/images/promosi/
     */
    public function gambarUrl(): ?string
    {
        return $this->gambar ? url('images/promosi/' . $this->gambar) : null;
    }
}