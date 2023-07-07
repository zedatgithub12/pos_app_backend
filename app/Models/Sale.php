<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference',
        'user',
        'shop',
        'customer',
        'items',
        'payment_status',
        'payment_method',
        'note',
        'grandtotal',
        'date',
        'time'
    ];

}