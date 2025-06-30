<?php

namespace App\Http\Controllers\ContactUs;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContactUsController extends Controller
{
    /**
     * Store a new contact us message
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'message' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            
            // Create the contact message
            $contactMessage = ContactUs::create([
                'user_id' => $user->id,
                'message' => $request->message,
                'is_read' => false,
            ]);


            return response()->json([
                'status' => true,
                'message' => 'Your message has been sent successfully',
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while submitting your message',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
