<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;

class UserService
{
    public function resetSeasonDebuff()
    {
        User::where('season_debuff', 1)->update(['season_debuff' => false]);
    }

    public function setSeasonDebuff(Collection $ids)
    {
        User::whereIn('id', $ids)->update(['season_debuff' => true]);
    }
}