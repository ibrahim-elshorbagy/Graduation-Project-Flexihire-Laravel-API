<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\JobList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $job = JobList::create([
            'user_id' => Auth::id(),
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

        return response()->json([
            'status' => true,
            'message' => 'Job created successfully',
            'job' => $job
        ], 201);
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

        $job = JobList::with('user')->find($id);

        $response = [
            'status' => true,
            'job' => $job,
            'has_applied' => false
        ];

        // Check if token is valid
        $user = Auth::guard('sanctum')->user();

        if ($user) {
            $hasApplied = \App\Models\User\JobApply::where('user_id', $user->id)
                ->where('job_id', $id)
                ->exists();
            $response['has_applied'] = $hasApplied;
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

        $job = JobList::find($id);

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

}
