<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeasonLeave extends Model
{
    use HasFactory;

    protected $fillable = [
        'season_id',
        'event_id',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function season()
    {
        return $this->belongsTo(Season::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
