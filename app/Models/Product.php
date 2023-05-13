<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'picture',
        'name',
        'category',
        'brand',
        'code',
        'cost',
        'unit',
        'price',
        'quantity',
        'description',
        'shop',
        'status'
    ];

    protected $casts = [
        'picture' => 'string'
    ];
}