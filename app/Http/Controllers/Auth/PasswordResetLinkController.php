<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
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

        // Attempt to send the password reset link
        $status = Password::sendResetLink($request->only('email'));

            $messages = [
            Password::RESET_LINK_SENT => 'A password reset link has been sent to your email address.',
            Password::INVALID_USER => 'We cannot find a user with that email address.',
        ];

        // Check if the password reset link was not sent
        if ($status != Password::RESET_LINK_SENT) {
            // Throw validation exception with the appropriate message
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        // Return a JSON response indicating success
        return response()->json(['status' => __($status)]);
    }
}
