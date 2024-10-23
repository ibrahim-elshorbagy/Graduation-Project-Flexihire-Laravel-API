<?php

namespace App\Http\Controllers\Auth\SignUpWith;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GoogleContoller extends Controller
{
    private function configureGoogleDriver($platform)
    {
        $config = config("services.google.$platform");

        config(['services.google' => [
            'client_id' => $config['client_id'],
            'client_secret' => $config['client_secret'],
            'redirect' => $config['redirect'],
        ]]);
    }

    public function redirectToGoogle(Request $request)
    {
        $platform = $request->query('platform', 'web');
        $this->configureGoogleDriver($platform);

        return Socialite::driver('google')->stateless()->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $platform = $request->query('platform', 'web');
            $this->configureGoogleDriver($platform);

            $socialiteUser = Socialite::driver('google')->stateless()->user();

            $existingUser = User::where('email', $socialiteUser->email)->first();


                if ($existingUser) {
                if (is_null($existingUser->google_id)) {
                    $existingUser->google_id = $socialiteUser->id;
                    $existingUser->save();
                }

                Auth::login($existingUser);
                $tokenResult = $existingUser->createToken('GoogleAuthToken');
                $token = $tokenResult->plainTextToken;

                $roles = $existingUser->roles->pluck('name')->toArray();

            return response()->json([
                    'status' => true,
                    'message' => 'Login successfully.',
                    'token' => $token,
                    'roles' => $roles,
                    'user' => [
                    'id' => $existingUser->id,
                    'name' => $existingUser->name,
                    'email' => $existingUser->email,
                ]
                ], 200);
            }else {
                $newUser = User::create([
                    'name' => $socialiteUser->name,
                    'email' => $socialiteUser->email,
                    'google_id' => $socialiteUser->id,
                    'password' => Hash::make('1231@#165E!#!#@$1625essful!@$16my')
                ]);

                Auth::login($newUser);
                $newUser->assignRole('user');
                $roles = $newUser->roles->pluck('name')->toArray();

                $token = $newUser->createToken('GoogleAuthToken')->accessToken;

                return response()->json([
                    'status' => true,
                    'message' => 'Registered successfully.',
                    'roles' => $roles,
                    'token' => $token,
                    'user' => [
                    'id' => $newUser->id,
                    'name' => $newUser->name,
                    'email' => $newUser->email,
                ]
                ], 200);
            }
        }  catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
