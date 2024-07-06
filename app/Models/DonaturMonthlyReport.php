<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonaturMonthlyReport extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table    = 'donatur_monthly_report';
    protected $fillable = [
        'donatur_id',
        'date', 
        'donate_count_all',
        'donate_sum_all',
        'donate_count_paid',
        'donate_sum_paid',
        'created_at',
        'updated_at'
    ];
}
