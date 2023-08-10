<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_picture',
        'item_name',
        'item_code',
        'item_category',
        'item_sub_category',
        'item_brand',
        'item_description',
        'item_price',
        'item_status',
    ];
}