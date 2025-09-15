<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table    = 'payout';
    protected $fillable = [
        'program_id',
        'status', 
        'nominal_request',
        'nominal_approved',
        'desc_request',
        'paid_at',
        'file_submit',
        'file_paid',
        'file_accepted',
        'created_at',
        'updated_at',

        // new column
        'bank_fee',
        'optimation_fee',
        'ads_fee',
        'platform_fee'
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }
}
