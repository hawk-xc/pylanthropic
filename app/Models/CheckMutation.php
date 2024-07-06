<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class CheckMutation extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table    = 'check_mutation';
    protected $fillable = [
        'bank_type',
        'apps_from', 
        'mutation_date',
        'mutation_type',
        'amount',
        'description',
        'transaction_id',
        'created_at',
        'updated_at'
    ];
}
