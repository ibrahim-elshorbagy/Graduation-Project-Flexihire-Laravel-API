<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\JobList;
use App\Models\User\JobApply;
use App\Notifications\JobStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MyJobsController extends Controller
{
    public function updateApplicationStatus(Request $request, $id)
    {
        try {
            $validator = Validator::make(
                array_merge($request->all(), ['id' => $id]),
                [
                    'id' => 'required|exists:job_applies,id',
                    'status' => 'required|in:pending,approved,rejected'
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $jobApply = JobApply::findOrFail((int) $id);

            // Check if the job belongs to the authenticated company
            if ( (int)$jobApply->job->user_id !==  (int)Auth::id()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized to update this application'
                ], 403);
            }

            // Get old status to check if it changed
            $oldStatus = $jobApply->status;
            
            $jobApply->update([
                'status' => $request->status
            ]);
            
            // Send notification if status changed
            if ($oldStatus !== $request->status) {
                // Get the job title
                $jobTitle = $jobApply->job->title;
                $jobId = $jobApply->job->id;
                
                // Get the applicant
                $applicant = $jobApply->user;
                
                // Send notification directly to the applicant
                $applicant->notify(new JobStatusNotification($jobTitle, $request->status, $jobId));
            }

            return response()->json([
                'status' => true,
                'message' => 'Application status updated successfully',
                'data' => $jobApply
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating application status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function myJobs()
    {

        $jobs = JobList::where('user_id', Auth::id())
            ->withCount('applies as applications_count')
            ->orderBy('date_posted', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Jobs retrieved successfully',
            'data' => $jobs,
        ]);
    }

    public function myJobProposals($id)
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

        $job = JobList::where('id', (int)$id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ( (int)$job->user_id !== (int)auth()->id()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized to see this proposals'
            ], 403);
        }
        $proposals = $job->applies()
            ->with('user')
            ->paginate(10);

        return response()->json([
            'message' => 'Job proposals retrieved successfully',
            'data' => $proposals
        ]);
    }
}
