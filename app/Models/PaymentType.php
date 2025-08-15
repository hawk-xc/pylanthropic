<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class PaymentType extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table    = 'payment_type';
    protected $fillable = [
        'key',
        'name',
        'img',
        'target_number',
        'target_desc',
        'is_active',
        'sort_number',
        'type',
        'payment_code',
        'created_at',
        'updated_at'
    ];

    public function donatur_loyal()
    {
        return $this->hasMany(DonaturLoyal::class, 'payment_type_id', 'id');
    }
}
