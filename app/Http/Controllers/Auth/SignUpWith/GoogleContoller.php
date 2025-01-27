<?php

namespace App\Http\Controllers\Auth\SignUpWith;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
        // Validate the incoming request
        $validateUser = Validator::make($request->all(), [
            'role' => 'required|in:user,company',
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors(),
            ], 422);
        }

        // Retrieve platform and role from the request
        $platform = $request->query('platform', 'web');
        $role = $request->input('role');

        // Configure the Google driver
        $this->configureGoogleDriver($platform);

        // Encode the role into the state parameter
        $state = base64_encode(json_encode(['role' => $role]));

        // Redirect to Google OAuth with the custom state
        return Socialite::driver('google')
            ->with(['state' => $state])
            ->stateless()
            ->redirect();
    }



    public function handleGoogleCallback(Request $request)
    {
        try {
            // Retrieve platform from the request
            $platform = $request->query('platform', 'web');
            $this->configureGoogleDriver($platform);

            // Retrieve and decode the state parameter
            $state = $request->input('state');
            if (!$state) {
                return response()->json([
                    'status' => false,
                    'message' => 'Missing state parameter.',
                ], 400);
            }

            $stateData = json_decode(base64_decode($state), true);
            $role = $stateData['role'] ?? null;

            // Validate the role
            $validateUser = Validator::make(['role' => $role], [
                'role' => 'required|in:user,company',
            ]);

            if ($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validateUser->errors(),
                ], 422);
            }

            // Proceed with Socialite user retrieval
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
                $permissions = $existingUser->getAllPermissions()->pluck('name');


                return response()->json([
                    'status' => true,
                    'message' => 'Login successfully.',
                    'token' => $token,
                    'roles' => $roles,
                    'user' => [
                        'id' => $existingUser->id,
                        'first_name' => $existingUser->first_name,
                        'last_name' => $existingUser->last_name,
                        'email' => $existingUser->email,
                        'image_url' => $existingUser->image_url,
                        'background_url' => $existingUser->background_url,
                        'cv' => $existingUser->cv,
                        'roles' => $roles,
                        'permissions' => $permissions
                    ]
                ], 200);
            } else {
                // Create a new user with the retrieved role
                $newUser = User::create([
                    'first_name' => explode(' ', $socialiteUser->name)[0],
                    'last_name' => explode(' ', $socialiteUser->name)[1],
                    'email' => $socialiteUser->email,
                    'google_id' => $socialiteUser->id,
                    'password' => Hash::make('Ha41w56d15613123'),
                ]);

                Auth::login($newUser);

                // Assign the selected role to the new user
                $newUser->assignRole($role);
                $roles = $newUser->roles->pluck('name')->toArray();

                $token = $newUser->createToken('GoogleAuthToken')->plainTextToken;
                $roles = $newUser->roles->pluck('name');
                $permissions = $newUser->getAllPermissions()->pluck('name');

                return response()->json([
                    'status' => true,
                    'message' => 'Registered successfully.',
                    'roles' => $roles,
                    'token' => $token,
                    'user' => [
                        'id' => $newUser->id,
                        'first_name' => $newUser->first_name,
                        'last_name' => $newUser->last_name,
                        'email' => $newUser->email,
                        'image_url' => $newUser->image_url,
                        'background_url' => $newUser->background_url,
                        'cv' => $newUser->cv,
                        'roles' => $roles,
                        'permissions' => $permissions

                    ]
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }



}
