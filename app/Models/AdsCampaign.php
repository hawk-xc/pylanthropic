<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class AdsCampaign extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table     = 'ads_campaign';
    protected $keyType   = 'string';
    public $incrementing = false;
    protected $fillable  = [
        'program_id',
        // 'lp_url',
        'adaccount_id',
        'ref_code',
        'name', 
        'is_active',
        'budget',
        'spend',
        'cpr',
        'result',
        'start_time',
        'updated_time',
        'budget_remaining',
        'updated_at',
        'created_at'
    ];
}
