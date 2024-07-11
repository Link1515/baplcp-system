<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sub_title',
        'enabled',
        'can_register_all_event',
        'price',
        'register_start_at',
        'register_end_at',
        'max_participants',
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
