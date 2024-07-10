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
        'can_register_all',
        'price',
        'register_start_datetime',
        'register_end_datetime',
        'max_capacity',
        'previous_event_group_id'
    ];
}
