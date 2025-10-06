<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpamLog extends Model
{
    protected $fillable = [
        'transaction_id',
        'device_id',
        'ua_core',
        'ip_address',
        'reason',
    ];
}
