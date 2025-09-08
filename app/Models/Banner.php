<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'image',
        'type',
        'alt',
        // 'duration',
        'expire_date',
        'is_forever',
        'is_publish',
        'description',
        'created_by'
    ];
}
