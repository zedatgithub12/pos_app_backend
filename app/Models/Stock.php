<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_code',
        'item_name',
        'stock_shop',
        'stock_cost',
        'stock_unit',
        'stock_min_quantity',
        'stock_price',
        'stock_quantity',
        'stock_expire_date',
        'stock_status',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}