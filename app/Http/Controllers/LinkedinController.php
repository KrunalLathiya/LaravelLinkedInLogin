<?php

namespace App\Http\Controllers;

use Auth;
use Exception;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Http\Request;

class LinkedinController extends Controller
{
    public function linkedinRedirect()
    {
        return Socialite::driver('linkedin-openid')->redirect();
    }

    public function linkedinCallback(Request $request)
    {
        try {
            // First, check if we received the code parameter.
            if (!$request->has('code')) {
                throw new Exception('Authorization request returned without a code parameter.');
            }

            // Get the user from LinkedIn using the code.
            $user = Socialite::driver('linkedin-openid')->stateless()->user();

            $linkedinUser = User::where('oauth_id', $user->id)->first();

            if ($linkedinUser) {
                Auth::login($linkedinUser);
                return redirect('/home');
            } else {
                $user = User::create([
                    'name' => $user->name,
                    'email' => $user->email,
                    'password' => md5(rand(1, 10000)),
                    'oauth_id' => $user->id,
                    'oauth_type' => 'linkedin',
                ]);

                Auth::login($user);
                return redirect('/home');
            }
        } catch (Exception $e) {
            // Optionally, handle or log the error more appropriately.
            dd($e->getMessage());
        }
    }
}
