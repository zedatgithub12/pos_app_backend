<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Replanish extends Model
{
    use HasFactory;

    protected $fillable = ["shop_id", "shop_name", "user_id", "stock_id", "stock_name", "stock_code", "existing_amount", "added_amount"];
}