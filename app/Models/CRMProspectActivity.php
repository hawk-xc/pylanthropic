<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\CRMProspect;

class CRMProspectActivity extends Model
{
    use HasFactory;

    protected $table = 'crm_prospect_activity';

    protected $fillable = [
        'type',
        'content',
        'description',
        'created_by',
        'updated_by',
        'date',
        'crm_prospect_id',
    ];

    public function crm_prospect()
    {
        return $this->belongsTo(CRMProspect::class, 'id', 'crm_prospect_id');
    }
}
