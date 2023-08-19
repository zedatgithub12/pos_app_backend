<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackagedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'stock_id',
        'item_name',
        'item_code',
        'item_quantity',
        'item_sku',
    ];

    public function packages()
    {
        return $this->belongsTo(Packages::class);
    }
}
