<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'place',
        'enabled',
        'price',
        'total_participants',
        'non_member_participants',

        'can_register_all_events',
        'register_start_at',
        'register_end_at',
        'register_all_participants',
        'register_all_price',
        'previous_event_group_id'
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function registrations()
    {
        return $this->hasMany(EventGroupRegistration::class);
    }

    public function previous()
    {
        return $this->belongsTo(EventGroup::class, 'previous_event_group_id');
    }

    public function next()
    {
        return $this->hasOne(EventGroup::class, 'previous_event_group_id');
    }
}
