<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class TrackingVisitor extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table    = 'tracking_visitor';
    protected $fillable = [
        'program_id',
        'visitor_code',
        'page_view',
        'nominal',
        'payment_type_id',
        'ref_code',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'created_at',
        'updated_at'
    ];
}
