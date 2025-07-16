<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\GrabProgram;

class GrabOrganization extends Model
{
    use HasFactory;

    protected $table = 'grab_organization';

    protected $fillable = [
        'user_id',
        'name',
        'avatar',
        'domicile',
        'address',
        'fb_pixel',
        'gtm',
        'twitter',
        'instagram',
        'facebook',
        'youtube',
        'description',
        'email',
        'phone',
        'is_partner',
        'platform',
        'is_interest',
    ];

    public function grab_programs()
    {
        return $this->hasMany(GrabProgram::class, 'grab_organization_id', 'id');
    }
}
