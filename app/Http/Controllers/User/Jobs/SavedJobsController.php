<?php

namespace App\Http\Controllers\User\Jobs;

use App\Http\Controllers\Controller;
use App\Models\JobList;
use App\Models\User;
use App\Models\User\SavedJobList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SavedJobsController extends Controller
{
    /**
     * Toggle job saved status using Laravel's toggle method
     */
    public function toggleSavedJob(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'job_id' => 'required|exists:job_lists,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::find(Auth::id());
            $jobId = $request->job_id;

            $result = $user->savedJobs()->toggle($jobId);

            // Check if the job was attached or detached
            $isSaved = count($result['attached']) > 0;
            $message = $isSaved ? 'Job saved successfully' : 'Job removed from saved list successfully';

            return response()->json([
                'status' => true,
                'message' => $message,
                'is_saved' => $isSaved
            ], $isSaved ? 201 : 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while toggling saved job status',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getSavedJobs(Request $request)
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
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $perPage = $request->query('per_page', 10);
            $search = $request->query('search', '');
            $searchLocation = $request->query('searchLocation', '');
            $minSalary = $request->query('minSalary');
            $maxSalary = $request->query('maxSalary');
            $skills = $request->query('skills', '');

            $savedJobs = SavedJobList::with(['job.user'])
                ->where('user_id', Auth::id())
                ->whereHas('job', function ($query) use ($search, $searchLocation, $skills, $minSalary, $maxSalary) {
                    $query->when($search !== '', function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%");
                    })
                    ->when($searchLocation !== '', function ($q) use ($searchLocation) {
                        $q->where('location', 'like', "%{$searchLocation}%");
                    })
                    ->when($skills !== '', function ($q) use ($skills) {
                        $q->whereJsonContains('skills', $skills);
                    })
                    ->when(!is_null($minSalary), function ($q) use ($minSalary) {
                        $q->where('min_salary', '>=', $minSalary);
                    })
                    ->when(!is_null($maxSalary), function ($q) use ($maxSalary) {
                        $q->where('max_salary', '<=', $maxSalary);
                    });
                })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'status' => true,
            'message' => 'Saved jobs retrieved successfully',
            'data' => $savedJobs->through(function($savedJob) {
                return [
                    'id' => $savedJob->id,
                    'job' => $savedJob->job,
                    'company' => $savedJob->job->user,
                    'saved_at' => $savedJob->created_at
                ];
            })
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'An error occurred while retrieving saved jobs',
            'error' => $e->getMessage()
        ], 500);
    }
}
}
