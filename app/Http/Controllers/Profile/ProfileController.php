<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ProfileController extends Controller
{


    public function UpdateName(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
        ]);


        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors(),
            ], 422);
        }

        $user = auth()->user()->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ]);

        return response()->json([
                'status' => true,
                'message' => 'User Name Updated Successfully',
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
            ], 201);


    }

    public function updateDescription(Request $request)
    {
        $validateUser = Validator::make($request->all(), [
            'description' => ['required', 'string'],
        ]);


        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors(),
            ], 422);
        }

        $user = auth()->user()->update([
            'description' => $request->description,
        ]);

        return response()->json([
                'status' => true,
                'message' => 'User Description Updated Successfully',
                'description' => $user->description,
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

        // Handle images upload
        $manager = new ImageManager(new Driver());
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Generate the directory path
            $directoryPath = 'User/' . $user->id . '/profile/profile_image';

            // Ensure the directory exists
            if (!Storage::disk('public')->exists($directoryPath)) {
                Storage::disk('public')->makeDirectory($directoryPath, 0755, true);
            }

            // Generate the full image path
            $imagePath = $directoryPath . '/' . uniqid('user_') . '.' . $image->getClientOriginalExtension();

            // Read the image using Intervention Image
            $img = $manager->read($image);

            // Save the image with compression
            $fullPath = Storage::disk('public')->path($imagePath);
            $img->save($fullPath, 80);

            // Get the public URL of the stored image
            $imageUrl = config('app.url') . Storage::url($imagePath);

            // Save the complete URL in the database
            $user->image_url = $imageUrl;
            $user->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Image updated successfully',
            'image_url' => $user->image_url,
        ], 200);
    }








    public function updateBackgroundImage(Request $request)
    {
        $user = Auth::user();

        $data = Validator::make($request->all(), [
            'background_image' => ['required', 'image'],
        ]);

        // Check for validation errors
        if ($data->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $data->errors(),
            ], 422);
        }

        // Handle background image upload
        $manager = new ImageManager(new Driver());
        if ($request->hasFile('background_image')) {
            $image = $request->file('background_image');
            // Generate the directory path
            $directoryPath = 'User/' . $user->id . '/profile/background_image';

            // Ensure the directory exists
            if (!Storage::disk('public')->exists($directoryPath)) {
                Storage::disk('public')->makeDirectory($directoryPath, 0755, true);
            }

            // Generate the full image path
            $imagePath = $directoryPath . '/' . uniqid('background_') . '.' . $image->getClientOriginalExtension();

            // Read the image using Intervention Image
            $img = $manager->read($image);

            // Save the image with compression
            $fullPath = Storage::disk('public')->path($imagePath);
            $img->save($fullPath, 80);

            // Get the public URL of the stored image
            $backgroundUrl = config('app.url') . Storage::url($imagePath);

            // Save the complete URL in the database
            $user->background_url = $backgroundUrl;
            $user->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Background image updated successfully',
            'background_url' => $user->background_url,
        ], 200);
    }














    public function updateCV(Request $request)
    {
        $user = Auth::user();

        $data = Validator::make($request->all(), [
            'cv' => ['required', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        // Check for validation errors
        if ($data->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $data->errors(),
            ], 422);
        }

        // Handle cv upload
        if ($request->hasFile('cv')) {
            $cv = $request->file('cv');
            // Generate the directory path
            $directoryPath = 'User/' . $user->id . '/profile/cv';

            // Ensure the directory exists
            if (!Storage::disk('public')->exists($directoryPath)) {
                Storage::disk('public')->makeDirectory($directoryPath, 0755, true);
            }

            // Generate filename
            $filename = uniqid('cv_') . '.' . $cv->getClientOriginalExtension();

            // Store the file directly
            Storage::disk('public')->putFileAs($directoryPath, $cv, $filename);

            // Generate the path for database
            $cvPath = $directoryPath . '/' . $filename;

            // Get the complete URL with domain
            $cvUrl = config('app.url') . Storage::url($cvPath);

            // Save the complete URL in the database
            $user->cv = $cvUrl;
            $user->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'CV updated successfully',
            'cv' => $user->cv,
        ], 200);
    }
}
