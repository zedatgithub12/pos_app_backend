<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransferedItems extends Model
{
    use HasFactory;
    protected $fillable = [
        'transfer_id',
        'item_name',
        'item_code',
        'item_unit',
        'item_price',
        'existing_amount',
        'transfered_amount',
    ];

    public function transfer()
    {
        return $this->belongsTo(StockTransfer::class);
    }
}