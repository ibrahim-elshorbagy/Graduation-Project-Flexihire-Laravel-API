<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\JobList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JobListController extends Controller
{
    public function index()
    {
        $jobs = JobList::with('user')->get();
        return response()->json(['jobs' => $jobs]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string',
            'location' => 'required|string',
            'description' => 'required|string',
            'skills' => 'required|string',
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

        $job = JobList::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'location' => $request->location,
            'description' => $request->description,
            'skills' => $request->skills,
            'date_posted' => now(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Job created successfully',
            'job' => $job
        ], 201);
    }

    public function show($id)
    {
        $job = JobList::with('user')->find($id);

        if (!$job) {
            return response()->json([
                'status' => false,
                'message' => 'JobList not found'
            ], 404);
        }

        return response()->json(['job' => $job]);
    }

    public function update(Request $request, $id)
    {
        $job = JobList::find($id);

        if (!$job) {
            return response()->json([
                'status' => false,
                'message' => 'Job not found'
            ], 404);
        }

        if ($job->user_id !== auth()->id()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized to update this job'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string',
            'location' => 'sometimes|string',
            'description' => 'sometimes|string',
            'skills' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $job->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Job updated successfully',
            'job' => $job
        ]);
    }

    public function destroy($id)
    {
        $job = JobList::find($id);

        if (!$job) {
            return response()->json([
                'status' => false,
                'message' => 'Job not found'
            ], 404);
        }

        if ($job->user_id !== auth()->id()) {
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

}
