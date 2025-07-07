<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CRMPipeline;

class CRMLeads extends Model
{
    use HasFactory;

    protected $table = 'crm_leads';

    protected $fillable = [
        'name',
        'created_by',
        'created_by',
        'sort_number',
        'updated_by',
    ];

    public function crm_pipelines() 
    {
        return $this->hasMany(CRMPipeline::class, 'crm_leads_id', 'id');
    } 
}
