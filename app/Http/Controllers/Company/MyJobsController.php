<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\JobList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MyJobsController extends Controller
{
    public function myJobs()
    {
        
        $jobs = JobList::select('id', 'title', 'date_posted')
            ->where('user_id', Auth::id())
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

        $job = JobList::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($job->user_id !== auth()->id()) {
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
