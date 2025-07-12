<?php

namespace App\Http\Controllers\Review;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use App\Notifications\NewReviewNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Create a new review for a company
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'company_id' => 'required|exists:users,id',
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'required|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $userId = $user->id; // Explicitly get the user ID as an integer
            $companyId = (int)$request->company_id; // Cast to integer to ensure it's not a string

            // company
            if ($user->hasRole('company')) {
                return response()->json([
                    'status' => false,
                    'message' => 'company cannot review companies'
                ], 422);
            }

            // Check if the ID is for a company
            $company = User::findOrFail($companyId);
            if (!$company->hasRole('company')) {
                return response()->json([
                    'status' => false,
                    'message' => 'You can only review companies'
                ], 422);
            }

            // Don't allow reviewing yourself
            if ($userId === $companyId) {
                return response()->json([
                    'status' => false,
                    'message' => 'You cannot review yourself'
                ], 422);
            }

            // Check if user already reviewed this company
            $existingReview = Review::where('user_id', $userId)
                ->where('company_id', $companyId)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'status' => false,
                    'message' => 'You have already reviewed this company',
                    'review' => $existingReview
                ], 422);
            }

            // Create the review with explicit integer IDs
            $review = Review::create([
                'user_id' => $userId,
                'company_id' => $companyId,
                'rating' => (int)$request->rating,
                'comment' => $request->comment,
            ]);

            // Load the user relationship
            $review->load('user:id,first_name,last_name,image_url');

            // Notify the company
            $company->notify(new NewReviewNotification($user, $review));

            return response()->json([
                'status' => true,
                'message' => 'Review submitted successfully',
                'review' => $review
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while submitting your review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a review
     */
    public function destroy($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|exists:reviews,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid review ID',
                    'errors' => $validator->errors()
                ], 422);
            }

            $review = Review::findOrFail($id);

            // Check if the authenticated user owns this review
            if ((int)$review->user_id !== (int)Auth::id()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized to delete this review'
                ], 403);
            }

            $review->delete();

            return response()->json([
                'status' => true,
                'message' => 'Review deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while deleting the review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get reviews written by the authenticated user
     */
    public function getUserReviews(Request $request)
    {
        try {
            $user = Auth::user();

            $reviews = $user->writtenReviews()
                ->with('company')
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return response()->json([
                'status' => true,
                'message' => 'User reviews retrieved successfully',
                'data' => $reviews
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a specific review by ID
     */
    public function show($id)
    {
        try {
            $validator = Validator::make(['id' => $id], [
                'id' => 'required|exists:reviews,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid review ID',
                    'errors' => $validator->errors()
                ], 422);
            }

            $review = Review::with(['user', 'company'])
                ->findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Review retrieved successfully',
                'data' => $review
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving the review',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
