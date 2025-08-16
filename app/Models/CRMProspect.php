<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CRMPipeline;
use App\Models\CRMProspectActivity;
use App\Models\CRMProspectLogs;
use App\Models\Donatur;

class CRMProspect extends Model
{
    use HasFactory;

    protected $table = 'crm_prospect';

    protected $fillable = [
        'name',
        'description',
        'nominal',
        'prospect_type',
        'assign_to',
        'is_potential',
        'updated_by',
        'crm_pipeline_id',
        'donatur_id',
        'created_by',
    ];

    public function crm_pipeline()
    {
        return $this->belongsTo(CRMPipeline::class, 'id', 'crm_pipeline_id');
    }

    public function crm_prospect_activities()
    {
        return $this->hasMany(CRMProspectActivity::class, 'crm_prospect_id', 'id');
    }

    public function crm_prospect_donatur()
    {
        return $this->belongsTo(Donatur::class, 'donatur_id', 'id');
    }

    public function crm_prospect_logs()
    {
        return $this->hasMany(CRMProspectLogs::class, 'crm_prospect_id', 'id');
    }

    public function crm_prospect_pic()
    {
        return $this->belongsTo(User::class, 'assign_to');
    }
}
