<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;

class Donatur extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table    = 'donatur';
    protected $fillable = [
        'name',
        'telp',
        'want_to_contact',
        'wa_inactive_since',
        'email',
        'password',
        'password_reset',
        'last_donate_paid',
        'count_donate_paid',
        'sum_donate_paid',
        'wa_campaign',
        'ref_code',
        'created_at',
        'is_muslim',
        // new migration
        'religion'
    ];

    // add model relation method
    public function chat()
    {
        return $this->hasMany(Chat::class, 'donatur_id', 'id');
    }

    public function transaction()
    {
        return $this->hasMany(Transaction::class, 'donatur_id', 'id');
    }

    public function donaturLoyal()
    {
        return $this->hasMany(DonaturLoyal::class, 'donatur_id', 'id');
    }

    public function leadsCRM()
    {
        return $this->hasMany(leadsCRM::class, 'donatur_id', 'id');
    }
}

