<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';
    protected $primaryKey = 'id';

    protected $fillable = [
        'package_name',
        'payment_term',
        'activation_date',
        'expire_date'
    ];

    protected $guarded = [];
}