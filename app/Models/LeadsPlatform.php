<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\GrabProgram;

class LeadsPlatform extends Model
{
    use HasFactory;

    protected $table = 'grabdo_platform';

    // fillable column
    protected $fillable = [
        'id',
        'name',
        'is_grab',
        'url',
        'is_active'
    ];

    public function grab_programs()
    {
        return $this->hasMany(GrabProgram::class, 'platform_id', 'id');
    }
}
