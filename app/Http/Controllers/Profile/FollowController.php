<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FollowController extends Controller
{

    public function toggleFollow(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'company_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = Auth::user();
            $companyId = $request->company_id;

            // Check if the ID is for a company
            $company = User::findOrFail($companyId);
            if (!$company->hasRole('company')) {
                return response()->json([
                    'status' => false,
                    'message' => 'You can only follow companies'
                ], 422);
            }

            // Don't allow following yourself
            if ((int)$user->id === (int)$companyId) {
                return response()->json([
                    'status' => false,
                    'message' => 'You cannot follow yourself'
                ], 422);
            }

            $result = $user->followedCompanies()->toggle($companyId);

            // Check if the company was followed or unfollowed
            $isFollowing = count($result['attached']) > 0;
            $message = $isFollowing ? 'Company followed successfully' : 'Company unfollowed successfully';



            return response()->json([
                'status' => true,
                'message' => $message,
                'is_following' => $isFollowing
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while toggling follow status',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getFollowedCompanies(Request $request)
    {
        try {
            $validator = Validator::make($request->query(), [
                'per_page' => 'nullable|integer|min:1|max:100',
                'search' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $perPage = $request->query('per_page', 10);
            $search = $request->query('search', '');

            $user = Auth::user();

            $followedCompanies = $user->followedCompanies()
                ->when($search !== '', function ($query) use ($search) {
                    $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
                })
                ->with(['roles'])
                // ->select('users.id', 'users.first_name', 'users.last_name', 'users.image_url', 'users.description', 'users.location')
                ->paginate($perPage);

            return response()->json([
                'status' => true,
                'message' => 'Followed companies retrieved successfully',
                'data' => $followedCompanies
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving followed companies',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
