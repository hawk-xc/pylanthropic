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
        'invoice_number ',
        'status',
        'nominal',
        'message',
        'payment_method',
        'paid_at',
        'created_at',
        'user_agent'
    ];
}
