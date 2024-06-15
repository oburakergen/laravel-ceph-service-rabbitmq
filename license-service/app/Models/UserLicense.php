<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'max_file_size',
        'max_file_count',
        'max_storage',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime:d.m.Y',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeExpiredAt($query, $date)
    {
        return $query->where('expires_at', '>' ,$date);
    }

}
