<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBucket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bucket_name',
        'bucket_region',
        'bucket_key',
        'bucket_secret',
        'user_id',
        'user_license_id'
    ];
}
