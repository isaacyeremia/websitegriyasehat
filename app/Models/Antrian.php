<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Antrian extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kode_antrian',
        'poli',
        'dokter',
        'tanggal',
        'keluhan',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
