<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\GrabOrganization;
use \App\Models\LeadsPlatform;

class GrabProgram extends Model
{
    use HasFactory;

    protected $table = 'grab_program';

    // fillable column
    protected $fillable = [
        'grab_organization_id',
        'id_grab',
        'user_id',
        'category_slug',
        'type',
        'name',
        'slug',
        'permalink',
        'headline',
        'content',
        'status',
        'target_status',
        'target_type',
        'target_at',
        'target_amount',
        'collect_amount',
        'remaining_amount',
        'over_at',
        'is_featured',
        'is_populer_search',
        'status_percent',
        'status_date',
        'image_url',
        'image_url_thumb',
        'total_donatur',
        'fb_pixel',
        'gtm',
        'toggle_dana',
        'program_created_at',
        'tags_name',
        'is_favorite',
        'fund_display',
        'is_interest',
        'is_taken',
    ];

    public function grab_organization()
    {
        return $this->belongsTo(GrabOrganization::class, 'grab_organization_id', 'id');
    }

    public function leads_platform()
    {
        return $this->belongsTo(LeadsPlatform::class, 'platform_id', 'id');
    }
}
