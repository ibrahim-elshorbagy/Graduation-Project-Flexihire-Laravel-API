<?php

namespace App\Http\Controllers\User\Jobs;

use App\Http\Controllers\Controller;
use App\Models\JobList;
use App\Models\User\JobApply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobApplyController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'job_id' => 'required|exists:job_lists,id',
                'proposal' => 'required|string|min:10|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $job = JobList::find($request->job_id);

            // Check if user has already applied for this job
            $existingApplication = JobApply::where('user_id', Auth::id())
                ->where('job_id', $request->job_id)
                ->first();

            if ($existingApplication) {
                return response()->json([
                    'status' => false,
                    'message' => 'You have already applied for this job'
                ], 422);
            }


            $jobApply = $job->applies()->create([
                'user_id' => Auth::id(),
                'proposal' => $request->proposal,
                'created_at'=>now()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Job application submitted successfully',
                'data' => $jobApply
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while submitting your application',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function myProposals()
    {
        try {
            $proposals = JobApply::with('job.user')
            ->where('user_id', Auth::id())
            ->select('id', 'job_id', 'proposal', 'created_at')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

            return response()->json([
                'status' => true,
                'message' => 'Job proposals retrieved successfully',
                'data' => $proposals->map(function($proposal) {
                    return [
                        'id' => $proposal->id,
                        'job' => $proposal->job,
                        'company' => $proposal->job->user,
                        'proposal_date' => $proposal->created_at,
                        'proposal' => $proposal->proposal
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving your proposals',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
