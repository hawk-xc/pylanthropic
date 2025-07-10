<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use App\Models\CRMProspect;
use App\Models\DonaturSortLink;

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

    public function crm_prospects()
    {
        return $this->hasMany(CRMProspect::class, 'donatur_id', 'id');
    }

    public function donatur_short_links()
    {
        return $this->hasMany(DonaturSortLink::class, 'donatur_id', 'id');
    }
}

