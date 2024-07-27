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

        $seasonRegistration = SeasonRegistration::with('season')->find($id);
        $season = $seasonRegistration->season;

        SeasonRegistration::destroy($id);

        if (
            Carbon::now()->gt(Carbon::parse($season->register_end_at))
        ) {
            return response(['title' => '已超過取消報名時間', 'text' => '已超過取消報名時間，若有緊急事件需要取消報名，請自行私訊管理員。'], 403);
        }
        return response([
            'title' => '您已成功取消',
            'text' => '若想再次報名，可於此頁在報名時間內重新報名，謝謝!'
        ], 200);
    }
}
