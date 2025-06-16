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
        'slug',
        'sort_number',
        'icon',
        'is_show ',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by'
    ];

    /**
     * Relasi many-to-many dengan Program melalui tabel pivot ProgramCategories
     */
    public function programs()
    {
        return $this->belongsToMany(Program::class, 'program_categories', 'program_category_id', 'program_id')
                    ->using(ProgramCategories::class)
                    ->withPivot('is_active')
                    ->withTimestamps();
    }

    /**
     * Relasi ke tabel pivot untuk akses langsung
     */
    public function programCategories()
    {
        return $this->hasMany(ProgramCategories::class, 'program_category_id');
    }
}
