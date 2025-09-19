<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenConfigLogs extends Model
{
    use HasFactory;

    protected $table = 'token_config_logs';

    protected $fillable = [
        'token_config_id',
        'description',
        'token',
        'created_by',
    ];

    // ORM
    public function tokenConfig()
    {
        return $this->belongsTo(TokenConfig::class, 'token_config_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
