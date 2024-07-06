<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class ProgramSpend extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table    = 'program_spend';
    protected $fillable = [
        'program_id',
        'title',
        'desc_publish', 
        'nominal_request',
        'nominal_approved ',
        'date_request',
        'type',
        'is_operational',
        'status',
        'date_approved',
        'approved_by',
        'transfer_proof',
        'desc_request',
        'ads_campaign_id',
        'created_at',
        'updated_at',
        'updated_by'
    ];
}
