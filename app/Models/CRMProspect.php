<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CRMPipeline;
use App\Models\CRMProspectActivity;

class CRMProspect extends Model
{
    use HasFactory;

    protected $table = 'crm_prospect';

    protected $fillable = [
        'name',
        'description',
        'nominal',
        'assign_to',
        'is_potential',
        'updated_by',
        'crm_pipeline_id',
        'donatur_id',
    ];

    public function crm_pipeline()
    {
        return $this->belongsTo(CRMPipeline::class, 'id', 'crm_pipeline_id');
    }

    public function crm_prospect_activity()
    {
        return $this->hasMany(CRMProspectActivity::class, 'crm_prospect_id', 'id');
    }
}
