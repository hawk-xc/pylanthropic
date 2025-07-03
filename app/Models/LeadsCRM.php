<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// relation model definition
use App\Models\Donatur;
use App\Models\Program;

class LeadsCRM extends Model
{
    use HasFactory;
    
    protected $table = 'leads_crm';

    // fillable column
    protected $fillable = [
        'donatur_id',
        'program_id',
        'lead_stage',
        'lead_stack',
        'description',
        'image'
    ];


    public function donatur() {
        return $this->belongsTo(Donatur::class, 'donatur_id', 'id');
    }

    public function program() {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }
}
