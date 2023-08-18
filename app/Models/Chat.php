<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table    = 'chat';
    protected $fillable = [
        'no_telp',
        'text', 
        'image',
        'token',
        'vendor',
        'url',
        'type',
        'status',
        'transaction_id',
        'donatur_id',
        'program_id',
        'updated_at',
        'created_at'
    ];
}
