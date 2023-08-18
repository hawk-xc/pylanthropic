<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class TransactionReal extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table    = 'transaction_real';
    protected $fillable = [
        'bank',
        'nominal', 
        'date_paid',
        'invoice_number',
        'status',
        'transaction_id',
        'created_at',
        'updated_at'
    ];
}
