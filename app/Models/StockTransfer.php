<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTransfer extends Model
{
    use HasFactory;

    protected $fillable = ["sendershopid", "sendershopname", "receivershopid", "receivershopname", "items", "note", "userid", "status"];
}