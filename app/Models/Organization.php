<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table    = 'organization';
    protected $fillable = [
        'uuid',
        'name', 
        'phone',
        'email',
        'password',
        'address',
        'status',
        'about',
        'logo',
        'pic_fullname',
        'pic_nik',
        'pic_image',
        'approved_at',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];
}
