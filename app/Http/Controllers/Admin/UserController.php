<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function getUsers(Request $request)
    {


        $users = User::role('user')->get();

        return response()->json([
            'message' => 'Users.',
            'data' => $users,
        ], 200);
    }


    public function getCompanies(Request $request)
    {

        $companies = User::role('company')->get();

        return response()->json([
            'message' => 'companies.',
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
                'cv'              => $user->cv,
                'skills'          => $user->skills ?? [],
                // Assuming a one-to-one relation or retrieving the first job entry.
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

        // Retrieve the company (user) or fail
        $company = User::findOrFail($id);

        // Verify the user has the role "company"
        if (!$company->hasRole('company')) {
            return response()->json([
                'message' => 'Company not found or not a valid company role.'
            ], 404);
        }


        return response()->json([
            'company' => [
                'id'              => $company->id,
                'first_name'      => $company->first_name,
                'last_name'       => $company->last_name,
                'email'           => $company->email,
                'image_url'       => $company->image_url,
                'background_url'  => $company->background_url,
                'cv'              => $company->cv,
                'skills'          => $company->skills ?? [],
                // Placeholder for job(s) information; can be expanded in the future.
                // 'job'             => $company->jobs[0] ?? null,
            ],
        ], 200);
    }


}
