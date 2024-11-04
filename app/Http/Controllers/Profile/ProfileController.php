<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
    public function updateImage(Request $request)
    {
        $user = Auth::user();

        $data = Validator::make($request->all(), [
            'image' => ['required', 'image'],
        ]);

        // Check for validation errors
        if ($data->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $data->errors(),
            ], 422);
        }

        // Delete the old image if it exists
        if ($user->image_url != null) {
                Storage::disk('public')->deleteDirectory('User/' . $user->id . '/profile_image');
        }

        // Store the new image
        $image = $request->file('image');
        if ($image) {
            $path = $image->store('User/' . $user->id . '/profile_image', 'public');
            $user->image_url = Storage::url($path);
            $user->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Image updated successfully',
            'image_url' => $user->image_url,
        ], 200);
    }
}
