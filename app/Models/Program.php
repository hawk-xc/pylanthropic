<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use \App\Models\ProgramCategory;
use Illuminate\Database\Eloquent\Model;

// model relation definition
use App\Models\LeadsCRM;

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
        'nominal_request',
        'nominal_approved',
        'approved_at',
        'approved_by',
        'end_date',
        'is_publish',
        'is_recommended',
        'is_show_home',
        'is_urgent',
        'count_amount_page',
        'count_view',
        'count_pra_checkout',
        'count_read_more',
        'optimation_fee',
        'show_minus',
        'donate_sum',
        'donate_sum_last_updated',
        'is_islami',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        // new column
        'same_as_thumbnail'
    ];

    /**
     * Relasi many-to-many dengan ProgramCategory melalui tabel pivot ProgramCategories
     */
    public function categories()
    {
        return $this->belongsToMany(ProgramCategory::class, 'program_categories', 'program_id', 'program_category_id')
            ->using(ProgramCategories::class)
            ->withPivot('is_active')
            ->withTimestamps();
    }

    /**
     * Relasi ke tabel pivot untuk akses langsung
     */
    public function programCategories()
    {
        return $this->hasMany(ProgramCategories::class, 'program_id');
    }

    public function programOrganization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function leadsCRM() {
        $this->hasMany(LeadsCRM::class, 'program_id', 'id');
    }

    public function donatur_short_links()
    {
        $this->hasMany(DonaturShortLink::class, 'program_id', 'id');
    }
}
