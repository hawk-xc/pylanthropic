<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class AdsCampaignHistory extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table    = 'ads_campaign_history';
    protected $fillable = [
        'ads_campaign_id',
        'result', 
        'cpr',
        'spend',
        'is_active',
        'budget',
        'budget_remaining',
        'frequency',
        'impressions',
        'clicks',
        'cpc',
        'cpm',
        'ctr',
        'reach',
        'updated_at',
        'created_at'
    ];
}
