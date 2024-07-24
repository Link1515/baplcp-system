<?php

namespace App\Http\Controllers;

use App\Http\Requests\SeasonRegistration\StoreSeasonRegistrationRequest;
use App\Models\Season;
use App\Models\SeasonRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SeasonRegistrationController extends Controller
{
    public function store(StoreSeasonRegistrationRequest $request)
    {
        $validated = $request->validated();

        $season = Season::find($validated['seasonId']);
        if (
            Carbon::now()->lte(Carbon::parse($season->register_start_at))
        ) {
            return redirect()->back()->with('error', '報名未開放');
        }

        if (Carbon::now()->gte(Carbon::parse($season->register_end_at))) {
            return redirect()->back()->with('error', '報名已截止');
        }

        $userId = $request->input('userId') ?? 1;

        $hasRegistered = SeasonRegistration::where('user_id', $userId)->where('season_id', $validated['seasonId'])->exists();

        if ($hasRegistered) {
            return redirect()->back()->with('error', '已報名季打');
        }

        SeasonRegistration::create([
            'user_id' => $userId,
            'season_id' => $validated['seasonId']
        ]);

        return redirect()->route('seasons.show', ['season' => $validated['seasonId']])->with('success', '報名成功');
    }

    public function destroy(string $id)
    {
        SeasonRegistration::destroy($id);
    }
}
