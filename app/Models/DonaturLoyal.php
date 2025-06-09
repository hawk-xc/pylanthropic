<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Donatur;
use App\Models\Program;

class DonaturLoyal extends Model
{
    use HasFactory;

    protected $table = 'donatur_loyal';

    protected $fillable = [
        'donatur_id',
        'program_id',
        'nominal',
        'payment_type_id',
        'desc',
        'every_period',
        'every_time',
        'every_date_period',
        'every_month_period',
        'every_date',
        'every_day',
        'is_active',
        'created_by',
        'updated_by',
    ];

    public function donatur()
    {
        return $this->belongsTo(Donatur::class, 'donatur_id', 'id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }
}
