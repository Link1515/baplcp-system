<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'season_id',
        'start_at',
        'register_start_at',
        'register_end_at',
        'total_participants',
    ];

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}
