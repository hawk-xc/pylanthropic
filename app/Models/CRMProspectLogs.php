<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CRMPipeline;
use App\Models\CRMProspect;

class CRMProspectLogs extends Model
{
    use HasFactory;

    protected $table = 'crm_prospect_logs';

    protected $fillable = [
        'pipeline_name',
        'created_by',
        'crm_prospect_id',
        'crm_pipeline_id'
    ];

    public function crm_pipeline()
    {
        return $this->belongsTo(CRMPipeline::class, 'id', 'crm_pipeline_id');
    }

    public function crm_prospect()
    {
        return $this->belongsTo(CRMProspect::class, 'id', 'crm_prospect_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
