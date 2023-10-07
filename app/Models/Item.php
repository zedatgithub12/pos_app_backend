<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_image',
        'item_name',
        'item_code',
        'item_category',
        'item_sub_category',
        'item_brand',
        'item_unit',
        'item_sku',
        'item_packaging',
        'item_description',
        'item_status',
    ];

    protected $casts = [
        'item_image' => 'string'
    ];
}