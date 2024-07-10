<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventGroupRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_group_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function eventGroup()
    {
        return $this->belongsTo(EventGroup::class);
    }
}
