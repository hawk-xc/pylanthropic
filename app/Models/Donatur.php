<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Donatur extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table    = 'donatur';
    protected $fillable = [
        'name',
        'telp', 
        'want_to_contact',
        'wa_inactive_since',
        'email',
        'password',
        'password_reset',
        'last_donate_paid',
        'count_donate_paid',
        'sum_donate_paid',
        'wa_campaign',
        'ref_code',
        'created_at',
        'is_muslim'
    ];
}
