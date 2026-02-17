<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'image',
        'category',
        'description',
        'tokopedia_link',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price'     => 'decimal:2',
    ];
}
