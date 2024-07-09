<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class LoginController extends Controller
{
    public function redirectToProvider()
    {
        if (Auth::check()) {
            return redirect()->intended('/');
        }

        return Socialite::driver('line')->redirect();
    }

    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('line')->user();
        } catch (\Throwable $e) {
            return redirect('/login/line');
        }

        $authUser = User::updateOrCreate([
            'line_id' => $user->getId(),
        ], [
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'line_id' => $user->getId(),
        ]);

        Auth::login($authUser, true);

        return redirect()->intended('/');
    }
}
