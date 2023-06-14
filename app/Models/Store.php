<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'category',
        'region',
        'city',
        'subcity',
        'address',
        'phone',
        'description',
        'profile_image',
        'manager_id',
        'manager',
        'status',
    ];

    protected $casts = [
        'profile_image' => 'string'
    ];
}