<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceUpdates extends Model
{
    use HasFactory;
    protected $fillable = ['productid', 'shopid', 'from', 'to', 'date', 'status'];
}