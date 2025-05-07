<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function getUsers(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'per_page' => 'nullable|integer|min:1|max:250',
            'search' => 'nullable|string|max:255',
            'jobSearch' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $perPage = $request->query('per_page', 10);
        $search = $request->query('search', '');
        $jobSearch = $request->query('jobSearch', '');

        $users = User::role('user')->with('jobs')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
            })
            ->when($jobSearch!== '', function ($query) use ($jobSearch) {
                $query->whereHas('jobs', function ($query) use ($jobSearch) {
                    $query->where('name', 'like', "%{$jobSearch}%");
                });
            })
            ->paginate($perPage);

        return response()->json([
            'message' => 'Users retrieved successfully.',
            'data' => $users,
        ], 200);
    }

    public function getCompanies(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'per_page' => 'nullable|integer|min:1|max:250',
            'search' => 'nullable|string|max:255',
            'searchLocation' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $perPage = $request->query('per_page', 10);
        $search = $request->query('search', '');
        $searchLocation = $request->query('searchLocation', '');

        $companies = User::role('company')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
            })
            ->when($searchLocation !== '', function ($query) use ($searchLocation) {
                $query->where('location',$searchLocation);
            })
            ->paginate($perPage);

        return response()->json([
            'message' => 'Companies retrieved successfully.',
            'data' => $companies,
        ], 200);
    }

    public function getUserInfo(Request $request, $id)
    {
        // Validate the id
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error entering user id',
                'errors' => $validator->errors(),
            ], 404);
        }

        // Retrieve the user or fail
        $user = User::findOrFail($id);

        // Verify the user has the role "user"
        if (!$user->hasRole('user')) {
            return response()->json([
                'message' => 'User not found or not a valid user role.'
            ], 404);
        }


        return response()->json([
            'user' => [
                'id'              => $user->id,
                'first_name'      => $user->first_name,
                'last_name'       => $user->last_name,
                'email'           => $user->email,
                'image_url'       => $user->image_url,
                'background_url'  => $user->background_url,
                'description'     => $user->description,
                'location'     => $user->location,
                'cv'              => $user->cv,
                'skills'          => $user->skills ?? [],
                'job'             => $user->jobs[0] ?? null,
            ],
        ], 200);
    }

    public function getCompanyInfo(Request $request, $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error entering company id',
                'errors' => $validator->errors(),
            ], 404);
        }

        $company = User::with('jobs')->findOrFail($id);

        if (!$company->hasRole('company')) {
            return response()->json([
                'message' => 'Company not found or not a valid company role.'
            ], 404);
        }

        return response()->json([
            'company' => [
                'id' => $company->id,
                'first_name' => $company->first_name,
                'last_name' => $company->last_name,
                'email' => $company->email,
                'image_url' => $company->image_url,
                'background_url' => $company->background_url,
                'description'     => $company->description,
                'location'     => $company->location,
                'cv' => $company->cv,
                'jobs' => $company->JobList
            ],
        ], 200);
    }


}
