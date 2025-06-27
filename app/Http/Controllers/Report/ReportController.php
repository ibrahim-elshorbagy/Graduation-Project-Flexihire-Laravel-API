<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Report\Report;
use App\Models\Report\ReportImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    /**
     * Create a new report
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'reported_user_id' => 'required|exists:users,id',
                'reason' => 'required|string|max:1000',
                'images' => 'nullable|array|max:5',
                'images.*' => 'image|mimes:jpeg,png,jpg',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $reporterId = $user->id;
            $reportedUserId = (int)$request->reported_user_id;

            // Check if the reported user exists
            $reportedUser = User::findOrFail($reportedUserId);

            // Don't allow reporting yourself
            if ($reporterId === $reportedUserId) {
                return response()->json([
                    'status' => false,
                    'message' => 'You cannot report yourself'
                ], 422);
            }

            // Create the report
            $report = Report::create([
                'reporter_id' => $reporterId,
                'reported_user_id' => $reportedUserId,
                'reason' => $request->reason,
            ]);

            // Handle image uploads if any
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('reports', 'public');
                    
                    ReportImage::create([
                        'report_id' => $report->id,
                        'image_path' => $imagePath,
                    ]);
                }
            }

            // Load relationships
            $report->load(['reporter:id,first_name,last_name', 'reportedUser:id,first_name,last_name', 'images']);

            return response()->json([
                'status' => true,
                'message' => 'Report submitted successfully',
                'report' => $report
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while submitting your report',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
