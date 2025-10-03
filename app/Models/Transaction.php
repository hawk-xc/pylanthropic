<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table    = 'transaction';
    protected $fillable = [
        'program_id',
        'donatur_id',
        'invoice_number',
        'status',
        'nominal',
        'nominal_code',
        'nominal_final',
        'message',
        'midtrans_token',
        'midtrans_url',
        'link',
        'payment_type_id',
        'paid_at',
        'is_show_name',
        'created_at',
        'updated_at',
        'user_agent',
        'device_id',
        'ua_core',
        'ip_address',
        'is_suspect',
        'ref_code'
    ];

    // update model relation method

    public function donatur()
    {
        return $this->belongsTo(Donatur::class, 'donatur_id');
    }
}
