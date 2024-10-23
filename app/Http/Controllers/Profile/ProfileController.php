<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function UpdateName(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'name' => ['required', 'string'],
        ]);


        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors(),
            ], 422);
        }

        $user = auth()->user()->update([
            'name' => $request->name,
        ]);

        return response()->json([
                'status' => true,
                'message' => 'User Name Updated Successfully',
            ], 201);


    }

    public function UpdatePassword(Request $request)
    {
        // Validate the request data
        $validateUser = Validator::make($request->all(), [
            'old_password' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors(),
            ], 422);
        }

        $user = auth()->user();

        // Check if the old password matches the current password
        if (!Hash::check($request->input('old_password'), $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'The provided old password is incorrect.',
            ], 422);
        }

        // Update the user's password
        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User Password Updated Successfully',
        ], 201);
    }
}
