<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopTarget extends Model
{
    use HasFactory;

    protected $fillable = ["userid", "shopid", "shopname", "r_daily", "r_monthly", "r_yearly", "start_date", "end_date", "status"];
}