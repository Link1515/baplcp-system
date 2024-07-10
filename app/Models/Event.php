<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_group_id',
        'title',
        'sub_title',
        'enabled',
        'price',
        'register_start_datetime',
        'register_end_datetime',
        'max_participants',
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
