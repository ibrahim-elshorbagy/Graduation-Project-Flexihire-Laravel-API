<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\JobList;
use App\Models\User\SavedJobList;
use App\Notifications\NewJobPostNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class JobListController extends Controller
{

    public function index(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'per_page' => 'nullable|integer|min:1|max:100',
            'search' => 'nullable|string|max:255',
            'searchLocation' => 'nullable|string|max:100',
            'minSalary' => 'nullable|integer|min:0',
            'maxSalary' => 'nullable|integer|min:0',
            'skills' => 'nullable|array|max:255',
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
        $minSalary = $request->query('minSalary');
        $maxSalary = $request->query('maxSalary');
        $skills = $request->query('skills', '');

        $jobs = JobList::with('user')
            ->when($search !== '', function ($query) use ($search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when($searchLocation !== '', function ($query) use ($searchLocation) {
                $query->where('location', 'like', "%{$searchLocation}%");
            })
            ->when($skills !== '', function ($query) use ($skills) {
                $query->whereJsonContains('skills', $skills);
            })
            ->when(!is_null($minSalary), function ($query) use ($minSalary) {
                $query->where('min_salary', '>=', $minSalary);
            })
            ->when(!is_null($maxSalary), function ($query) use ($maxSalary) {
                $query->where('max_salary', '<=', $maxSalary);
            })
            ->paginate($perPage);

        return response()->json([
            'message' => 'Jobs retrieved successfully',
            'data' => $jobs,
        ]);
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'location' => 'required|string|max:100',
            'description' => 'required|string',
            'skills' => 'array',
            'skills.*' => 'string',
            'min_salary' => 'nullable|integer|min:0',
            'max_salary' => 'nullable|integer|min:0|gt:min_salary',
            'salary_negotiable' => 'nullable|boolean',
            'payment_period' => 'nullable|in:hourly,daily,weekly,monthly,yearly',
            'payment_currency' => 'nullable|string|size:3',
            'hiring_multiple_candidates' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized to create job'
            ], 401);
        }

        try {
            // Get the authenticated company
            $company = Auth::user();

            // Create the job
            $job = JobList::create([
                'user_id' => $company->id,
                'title' => $request->title,
                'location' => $request->location,
                'description' => $request->description,
                'skills' => $request->skills,
                'min_salary' => $request->min_salary,
                'max_salary' => $request->max_salary,
                'salary_negotiable' => $request->salary_negotiable ?? false,
                'payment_period' => $request->payment_period,
                'payment_currency' => $request->payment_currency ?? 'USD',
                'hiring_multiple_candidates' => $request->hiring_multiple_candidates ?? false,
                'date_posted' => now(),
            ]);

            // Get all followers of the company
            $followers = $company->followers()->get();

            // Send notification to each follower
            foreach ($followers as $follower) {
                $follower->notify(new NewJobPostNotification($job, $company));
            }

            return response()->json([
                'status' => true,
                'message' => 'Job created successfully',
                'job' => $job
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while creating the job',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:job_lists,id'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid job ID',
                'errors' => $validator->errors(),
            ], 422);
        }

        $job = JobList::with('user')->find( (int) $id);

        $response = [
            'status' => true,
            'job' => $job,
            'has_applied' => false
        ];

        // Check if token is valid
        $user = Auth::guard('sanctum')->user();

        if ($user) {
            $hasApplied = \App\Models\User\JobApply::where('user_id', $user->id)
                ->where('job_id', (int) $id)
                ->exists();
            $response['has_applied'] = $hasApplied;
        }

        return response()->json($response);
    }





    public function authShowJob($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:job_lists,id'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid job ID',
                'errors' => $validator->errors(),
            ], 422);
        }

        $job = JobList::with('user')->find( (int) $id);

        $response = [
            'status' => true,
            'job' => $job,
            'has_applied' => false,
            'is_saved' => false
        ];

        // Check if token is valid
        $user = Auth::guard('sanctum')->user();

        if ($user) {
            $hasApplied = \App\Models\User\JobApply::where('user_id', $user->id)
                ->where('job_id', (int) $id)
                ->exists();
            $response['has_applied'] = $hasApplied;

            // Check if user has saved this job
            $isSaved = $user->savedJobs()->where('job_id', (int) $id)->exists();
            $response['is_saved'] = $isSaved;
        }

        return response()->json($response);
    }


    public function update(Request $request, $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:job_lists,id'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid job ID',
                'errors' => $validator->errors(),
            ], 422);
        }

        $job = JobList::find($id);


        if ((int)$job->user_id !==  (int)auth()->id()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized to update this job'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'location' => 'sometimes|string|max:100',
            'description' => 'sometimes|string',
            'skills' => 'array',
            'skills.*' => 'string',
            'min_salary' => 'nullable|integer|min:0',
            'max_salary' => 'nullable|integer|min:0|gt:min_salary',
            'salary_negotiable' => 'nullable|boolean',
            'payment_period' => 'nullable|in:hourly,daily,weekly,monthly,yearly',
            'payment_currency' => 'nullable|string|size:3',
            'hiring_multiple_candidates' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $data = $request->all();


        $job->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Job updated successfully',
            'job' => $job
        ]);
    }

    public function destroy($id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|integer|exists:job_lists,id'
        ]);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid job ID',
                'errors' => $validator->errors(),
            ], 422);
        }

        $job = JobList::find((int) $id);

        if ((int)$job->user_id !==  (int)auth()->id()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized to delete this job'
            ], 403);
        }

        $job->delete();

        return response()->json([
            'status' => true,
            'message' => 'Job deleted successfully'
        ]);
    }

    public function Ailist(Request $request)
    {
        // Get the authenticated user
        $user = Auth::user();
        $aiRecommendedIds = [];
        $userSkills = [];

        // Get user's skills if available
        if ($user) {
            $userSkills = $user->skills()->pluck('name')->toArray();
            $userSkillsString = implode(',', $userSkills);            if (!empty($userSkillsString)) {
                try {
                    // Get AI endpoint URL from config
                    $aiEndpoint = config('ai.recommendation_url');

                    // Only proceed if we have a valid URL
                    if (!empty($aiEndpoint)) {
                        Log::info("Using AI Recommendation URL: " . $aiEndpoint);

                        // Make the API call only if we have a valid URL
                        $aiResponse = Http::timeout(3)->post($aiEndpoint, [
                            'skills' => $user->description
                        ]);

                        // Check if the API call was successful
                        if ($aiResponse->successful()) {
                            $aiRecommendedIds = collect($aiResponse->json())->pluck('id')->toArray();
                        }
                    } else {
                        Log::info("No AI recommendation URL available, skipping API call");
                    }
                } catch (\Exception $e) {
                    Log::info("error calling AI recommendation API: " . $e->getMessage());
                    // Continue without AI recommendations if API fails
                }
            }
        }

        // If we have AI recommendations, return only the first 5 jobs
        if (!empty($aiRecommendedIds)) {
            $aiJobs = JobList::with('user')
                ->whereIn('id', $aiRecommendedIds)
                ->limit(10)
                ->get()
                ->map(function ($job) use ($user) {
                    $job->ai = true;
                    // Check if user has saved this job
                    if ($user) {
                        $job->is_saved = \App\Models\User\SavedJobList::where('user_id', $user->id)
                            ->where('job_id', $job->id)
                            ->exists();
                    } else {
                        $job->is_saved = false;
                    }
                    return $job;
                });

            return response()->json([
                'message' => 'AI recommended jobs retrieved successfully',
                'data' => $aiJobs,
            ]);
        } else {
            // If no AI recommendations, find only 5 jobs with similar skills to the user
            if (!empty($userSkills)) {
                $similarSkillJobs = JobList::with('user')
                    ->where(function ($query) use ($userSkills) {
                        foreach ($userSkills as $skill) {
                            $query->orWhereJsonContains('skills', $skill);
                        }
                    })
                    ->limit(10)
                    ->get()
                    ->map(function ($job) use ($user) {
                        // Check if user has saved this job
                        if ($user) {
                            $job->is_saved = \App\Models\User\SavedJobList::where('user_id', $user->id)
                                ->where('job_id', $job->id)
                                ->exists();
                        } else {
                            $job->is_saved = false;
                        }
                        return $job;
                    });

                return response()->json([
                    'message' => 'Similar skill jobs retrieved successfully',
                    'data' => $similarSkillJobs,
                ]);
            } else {
                // If user has no skills, return only 5 recent jobs
                $recentJobs = JobList::with('user')
                    ->orderBy('date_posted', 'desc')
                    ->limit(10)
                    ->get()
                    ->map(function ($job) use ($user) {
                        // Check if user has saved this job
                        if ($user) {
                            $job->is_saved = \App\Models\User\SavedJobList::where('user_id', $user->id)
                                ->where('job_id', $job->id)
                                ->exists();
                        } else {
                            $job->is_saved = false;
                        }
                        return $job;
                    });

                return response()->json([
                    'message' => 'Recent jobs retrieved successfully',
                    'data' => $recentJobs,
                ]);
            }
        }
    }
}
