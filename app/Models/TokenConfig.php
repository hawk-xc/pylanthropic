<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TokenConfig extends Model
{
    use HasFactory;

    protected $table = 'token_configs';

    protected $fillable = [
        'name',
        'token',
        'description'
    ];

    // ORM
    public function tokenConfigLogs()
    {
        return $this->hasMany(TokenConfigLogs::class, 'token_config_id', 'id');
    }
}
