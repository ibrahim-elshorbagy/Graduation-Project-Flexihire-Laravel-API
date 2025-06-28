<?php

namespace App\Http\Controllers\Company;

use App\Http\Controllers\Controller;
use App\Models\JobList;
use App\Models\User\JobApply;
use App\Notifications\JobStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser;

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

        if ( (int)$job->user_id !== (int)Auth::id()) {
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

    public function RankMyJobProposals(Request $request, $id)
    {

        try {
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

            if ((int)$job->user_id !== (int)Auth::id()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized to rank this job proposals'
                ], 403);
            }

            // Get all job applications for this job with users who have CVs
            $applications = $job->applies()
                ->with('user')
                ->whereHas('user', function($query) {
                    $query->whereNotNull('cv');
                })
                ->get();

            if ($applications->isEmpty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No applications with CVs found for this job'
                ], 404);
            }

            // Initialize PDF parser
            $parser = new Parser();
            $cvData = [];

            // Extract text from each applicant's CV
            foreach ($applications as $application) {
                $user = $application->user;
                

                if ($user->cv) {
                    try {
                        $pdf = $parser->parseFile($user->cv);
                        $text = $pdf->getText();
                        
                        $cvData[] = [
                            'user_id' => $user->id,
                            'text' => trim($text)
                        ];
                    } catch (\Exception $e) {
                        Log::error("Error parsing CV for user {$user->id}: " . $e->getMessage());
                        // Continue with other CVs even if one fails
                    }
                }
            }

            if (empty($cvData)) {
                return response()->json([
                    'status' => false,
                    'message' => 'No valid CVs could be processed'
                ], 404);
            }

            // Send to AI ranking service
            $rankedUserIds = [];
            $aiSimilarityData = [];
            try {
                $aiEndpoint = config('ai.rank_url');

                if (!empty($aiEndpoint)) {
                    // Format the payload with job description and cvs
                    $aiPayload = [
                        'job_description' => $job->description,
                        'top_k' => count($cvData),
                        'cvs' => $cvData
                    ];

                    $aiResponse = Http::timeout(90000)->post($aiEndpoint, $aiPayload);

                    Log::info($aiResponse);

                    if ($aiResponse->successful()) {
                        $aiResponseData = $aiResponse->json();
                        $rankedUserIds = collect($aiResponseData)->pluck('user_id')->toArray();
                        
                        // Create similarity mapping for easy lookup
                        foreach ($aiResponseData as $item) {
                            $aiSimilarityData[$item['user_id']] = $item['similarity'];
                        }
                        
                        Log::info("AI Ranking successful, received ranked user IDs: " . implode(', ', $rankedUserIds));
                    } else {
                        Log::error("AI Ranking API call failed: " . $aiResponse->body());
                    }
                } else {
                    Log::info("No AI ranking URL available, skipping API call");
                }
            } catch (\Exception $e) {
                Log::error("Error calling AI ranking API: " . $e->getMessage());
            }

            // If AI ranking succeeded, reorder applications based on AI response
            if (!empty($rankedUserIds)) {
                // Create a map of user_id to ranking position
                $rankingMap = array_flip($rankedUserIds);
                
                // Sort applications based on AI ranking
                $rankedApplications = $applications->sortBy(function($application) use ($rankingMap) {
                    $userId = $application->user->id;
                    // Return ranking position, or high number if not in ranking (put at end)
                    return isset($rankingMap[$userId]) ? $rankingMap[$userId] : 9999;
                })->values();
            } else {
                // If AI ranking failed, return applications in original order
                $rankedApplications = $applications;
            }

            // Paginate the results
            $page = $request->get('page', 1);
            $perPage = 10;
            $total = $rankedApplications->count();
            $offset = ($page - 1) * $perPage;
            
            $paginatedApplications = $rankedApplications->slice($offset, $perPage)->values();

            // Add similarity scores and ai flag to each user
            $paginatedApplications->each(function($application) use ($aiSimilarityData) {
                if ($application->user) {
                    $userId = $application->user->id;
                    $application->user->similarity = isset($aiSimilarityData[$userId]) ? $aiSimilarityData[$userId] : null;
                    $application->user->ai = true;
                }
            });

            // Format response similar to myJobProposals
            $response = [
                'current_page' => (int)$page,
                'data' => $paginatedApplications,
                'first_page_url' => request()->url() . '?page=1',
                'from' => $offset + 1,
                'last_page' => ceil($total / $perPage),
                'last_page_url' => request()->url() . '?page=' . ceil($total / $perPage),
                'next_page_url' => $page < ceil($total / $perPage) ? request()->url() . '?page=' . ($page + 1) : null,
                'path' => request()->url(),
                'per_page' => $perPage,
                'prev_page_url' => $page > 1 ? request()->url() . '?page=' . ($page - 1) : null,
                'to' => min($offset + $perPage, $total),
                'total' => $total
            ];

            return response()->json([
                'status' => true,
                'message' => 'Job proposals ranked successfully',
                'data' => $response,
                'ai_ranking_applied' => !empty($rankedUserIds)
            ]);

        } catch (\Exception $e) {
            Log::error("Error in RankMyJobProposals: " . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while ranking job proposals',
                'error' => $e->getMessage()
            ], 500);
        }
    }



}
