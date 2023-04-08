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
        'created_at'
    ];
}
