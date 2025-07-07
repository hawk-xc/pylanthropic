<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CRMProspect;
use App\Models\CRMLeads;

class CRMPipeline extends Model
{
    use HasFactory;

    protected $table = 'crm_pipeline';

    protected $fillable = [
        'name',
        'description',
        'percentage_deals',
        'type',
        'sort_number',
        'is_active',
        'created_by',
        'updated_by'
    ];

    public function crm_prospects()
    {
        return $this->hasMany(CRMProspect::class, 'crm_pipeline_id', 'id');
    }

    public function crm_lead()
    {
        return $this->belongsTo(CRMLeads::class, 'crm_leads_id', 'id');
    } 
}
