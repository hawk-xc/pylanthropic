<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table    = 'program';
    protected $fillable = [
        'organization_id',
        'title', 
        'slug ',
        'thumbnail',
        'image',
        'short_desc',
        'about',
        'approved_at',
        'approved_by',
        'end_date',
        'is_publish',
        'is_recommended',
        'is_show_home',
        'count_view',
        'count_pra_checkout',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by'
    ];
}
