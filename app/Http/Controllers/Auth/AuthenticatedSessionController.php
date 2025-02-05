<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Auth\OurJob;
use App\Models\Auth\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {

            // Attempt to authenticate using the LoginRequest
            $request->authenticate();

            // If authentication passes, proceed to token creation
            $user = $request->user();
            $user->tokens()->delete(); // Delete existing tokens if any

            // Create a new token
            $token = $user->createToken('auth_token')->plainTextToken;
            $roles = $user->roles->pluck('name');
            $permissions = $user->getAllPermissions()->pluck('name');

            return response()->json([
                'user' => [
                    'id' => $user->id,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'image_url' => $user->image_url,
                    'background_url' => $user->background_url,
                    'cv' => $user->cv,
                    'roles' => $roles,
                    'permissions'=>$permissions,
                    'skills' => $user->skills ?? [],
                    'job' => $user->jobs[0] ?? '',

                ],
                'token' => $token,
                'token_type' => 'Bearer',
            ]);
        try {
        } catch (AuthenticationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid credentials.',
            ], 401);

        }
        catch (\Throwable $th) {
            // Handle other unexpected errors
            return response()->json([
                'status' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        // Ensure user is authenticated
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Revoke all tokens issued to the current user
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.'
        ]);
    }


    public function getSkillsAndJobs(){
        $skills = Skill::all();
        $jobs = OurJob::all();

        return response()->json([
            'skills' => $skills,
            'jobs' => $jobs
        ]);
    }
    public function updateSkillsAndJobs(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'skill_id' => 'required|array',
            'skill_id.*' => 'exists:skills,id',
            'job_id' => 'required|exists:our_jobs_title,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Sync skills - this will remove any previously attached skills and attach only the new ones
        if ($request->has('skill_id')) {
            $user->skills()->sync($request->skill_id);
        }

        // Sync job - ensures the user has only one job at a time
        if ($request->has('job_id')) {
            $user->jobs()->sync([$request->job_id]);
        }

        $user->load('skills', 'jobs');

        return response()->json([
            'message' => 'User skills and jobs updated successfully.',
            'user' => $user,
        ]);
    }


    public function checkSkillAndJob(Request $request)
    {
        $user = $request->user();  // Get the authenticated user

        return response()->json([
            'message' => 'User role and permissions',
            'user_id' => $user->id,
            'name' => $user->name,
            'skills' => $user->skills->count() > 0,
            'job' => $user->jobs->count() > 0,
            'role' => $user->roles->pluck('name'),

        ]);
    }
    public function checkRole(Request $request)
    {
        $user = $request->user();  // Get the authenticated user

        // Initialize response data
        $response = [
            'user_id' => $user->id,
            'name' => $user->name,
            'skills' => [],
            'job' => null,
            'role' => '',
            'permissions' => [],
        ];

        // Check if user has skills
        if ($user->skills->isNotEmpty()) {
            foreach ($user->skills as $skill) {
                $response['skills'][] = [
                    'id' => $skill->id,
                    'name' => $skill->name,
                    'icon' => $skill->icon,
                ];
            }
        }

        if ($user->jobs->isNotEmpty()) {
            $job = $user->jobs->first();
            $response['job'] = [
                'id' => $job->id,
                'name' => $job->name,
            ];

        }

        $roles = $user->roles->pluck('name');

        // Return the response
        return response()->json([
            'message' => 'User role and permissions',
            'data' => $response,
        ]);
    }


}
