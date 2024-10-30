<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Notifications\CustomResetPasswordNotification;
use Illuminate\Support\Facades\DB;
class PasswordResetLinkController extends Controller
{

    public function store(Request $request): JsonResponse
    {
        // Validate the email address
        $validateUser = Validator::make($request->all(), [
            'email' => ['required', 'email'],
        ]);

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validateUser->errors(),
            ], 422);
        }

        // Generate a password reset token
        $token = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->input('email')],
            ['token' => bcrypt($token), 'created_at' => now()]
        );

        // Retrieve the user by email and send the notification
        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'We cannot find a user with that email address.',
            ], 404);
        }

        $user->notify(new CustomResetPasswordNotification($token,$user));

        return response()->json([
            'status' => true,
            'message' => 'A password reset link has been sent to your email address.',
        ], 200);
    }


}
