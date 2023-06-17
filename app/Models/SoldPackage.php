<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoldPackage extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference',
        'user',
        'shop',
        'customer',
        'p_name',
        'items',
        'tax',
        'discount',
        'payment_status',
        'payment_method',
        'note',
        'grandtotal',
        'date',
        'time'
    ];
}