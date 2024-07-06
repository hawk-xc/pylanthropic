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
    protected $table    = 'ads_campaign';
    protected $fillable = [
        'program_id',
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
