<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopTarget extends Model
{
    use HasFactory;

    protected $fillable = ["userid", "shopid", "shopname", "s_daily", "r_daily", "s_monthly", "r_monthly", "s_yearly", "r_yearly", "start_date", "end_date", "status"];
}