<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Donatur;
use App\Models\Program;

class DonaturShortLink extends Model
{
    use HasFactory;

    protected $table = 'donatur_short_links';

    protected $fillable = [
        'name',
        'code',
        'direct_link',
        'amount',
        'payment_type',
        'description',
        'created_by',
        'updated_by',
        'program_id',
        'donatur_id',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    public function donatur()
    {
        return $this->belongsTo(Donatur::class, 'donatur_id');

    }
}
