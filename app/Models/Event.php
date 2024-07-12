<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_group_id',
        'start_at',
        'register_start_at',
        'register_end_at',
        'member_participants',
        'non_member_participants',
    ];

    public function eventGroup()
    {
        return $this->belongsTo(EventGroup::class);
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }
}
