<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortLinkModel extends Model
{
    use HasFactory;

    protected $table = 'short_link';

    protected $fillable = [
        'name',
        'code',
        'direct_link',
        'description',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
