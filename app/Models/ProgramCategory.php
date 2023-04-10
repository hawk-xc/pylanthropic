<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class ProgramCategory extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table    = 'program_category';
    protected $fillable = [
        'name',
        'sort_number', 
        'is_show ',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];
}
