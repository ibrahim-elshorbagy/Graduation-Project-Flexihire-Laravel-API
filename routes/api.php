<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Ai\AiRankController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\SignUpWith\GoogleContoller;
use App\Http\Controllers\ChatSystem\ChatController;
use App\Http\Controllers\Company\JobListController;
use App\Http\Controllers\Company\MyJobsController;
use App\Http\Controllers\Notification\NotificationController;
use App\Http\Controllers\Profile\FollowController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Report\ReportController;
use App\Http\Controllers\Review\ReviewController;
use App\Http\Controllers\User\Jobs\JobApplyController;
use App\Http\Controllers\User\Jobs\SavedJobsController;
use Illuminate\Support\Facades\Broadcast;

//------------------------------ Login system -----------------------------------------//


Route::middleware(['guest','api'])->group(function () {

    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);

    Route::post('/reset-password', [NewPasswordController::class, 'store']);


    // Login with Google
    Route::get('/login-with-google',[GoogleContoller::class, 'redirectToGoogle']);
    Route::get('/google-callback',[GoogleContoller::class, 'handleGoogleCallback']);



    //get Icons + Jobs
    Route::get('/get-skills-jobs',[AuthenticatedSessionController::class, 'getSkillsAndJobs']);

});

//------------------------------ Profile -----------------------------------------//

Route::middleware(['auth:sanctum'])->group(function () {

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

    Route::post('/set-skills-job',[AuthenticatedSessionController::class, 'updateSkillsAndJobs']);
    Route::post('/check-skills-job',[AuthenticatedSessionController::class, 'checkSkillAndJob']);

    // Notification Routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'getAllNotifications']);
        Route::post('/mark-as-read/{id}', [NotificationController::class, 'markAsRead']);
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead']);
        Route::post('/delete/{id}', [NotificationController::class, 'deleteNotification']);
        Route::post('/delete-all', [NotificationController::class, 'deleteAllNotifications']);
    });


    // AI Recommended jobs
    Route::get('/ai/recommended-jobs', [JobListController::class, 'Ailist']);
    
    Route::get('/ai/read-my-cv', [AiRankController::class, 'index']);

    Route::prefix('profile')->group(function () {

        Route::post('/update-name',[ProfileController::class, 'updateName']);
        Route::post('/update-email',[ProfileController::class, 'updateEmail']);
        Route::post('/update-password',[ProfileController::class, 'updatePassword']);
        Route::post('/update-profile-image',[ProfileController::class, 'updateImage']);
        Route::post('/update-profile-background-image',[ProfileController::class, 'updateBackgroundImage']);
        Route::post('/update-profile-cv',[ProfileController::class, 'updateCV']);
        Route::post('/update-profile-description',[ProfileController::class, 'updateDescription']);
        Route::post('/update-profile-location',[ProfileController::class, 'updateLocationn']);
        Route::post('/update-skills-job',[AuthenticatedSessionController::class, 'updateSkillsAndJobs']);

        // Saved Jobs Routes - Simplified with toggle functionality
        Route::post('/saved-job/toggle-saved-job', [SavedJobsController::class, 'toggleSavedJob']);
        Route::get('/saved-job/saved-jobs', [SavedJobsController::class, 'getSavedJobs']);

    });

    // Follow Routes
    Route::post('follow/toggle-follow', [FollowController::class, 'toggleFollow']);
    Route::get('follow/followed-companies', [FollowController::class, 'getFollowedCompanies']);

    // Review Routes
    Route::prefix('reviews')->group(function () {
        Route::post('/create', [ReviewController::class, 'store']);
        Route::post('/delete/{id}', [ReviewController::class, 'destroy']);
        Route::get('/my-reviews', [ReviewController::class, 'getUserReviews']);
    });

    // Report Routes
    Route::prefix('reports')->group(function () {
        Route::post('/create', [ReportController::class, 'store']);
    });

    Route::get('/auth-companies', [UserController::class, 'authGetCompanies']);
    Route::get('/auth-jobs', [UserController::class, 'authGetJobs']);
    Route::get('/auth-company/{id}', [UserController::class, 'authGetCompanyInfo']);

    Route::get('/company/auth-get-job/{id}', [JobListController::class, 'authShowJob']);

});
//------------------------------ Anyone -----------------------------------------//


    Route::get('/users', [UserController::class, 'getUsers']);
    Route::get('/companies', [UserController::class, 'getCompanies']);
    Route::get('/top-companies', [UserController::class, 'getTopCompanies']);
    Route::get('/user/{id}', [UserController::class, 'getUserInfo']);
    Route::get('/company/{id}', [UserController::class, 'getCompanyInfo']);

//------------------------------ Anyone company -----------------------------------------//

    Route::get('/all-jobs', [JobListController::class, 'index']);

    Route::get('/company/get-job/{id}', [JobListController::class, 'show']);



//------------------------------ Only company -----------------------------------------//
    Route::middleware(['auth:sanctum', 'role:company'])->group(function () {

        Route::post('/company/create-job', [JobListController::class, 'store']);
        Route::post('/company/update-job/{id}', [JobListController::class, 'update']);
        Route::post('/company/delete-job/{id}', [JobListController::class, 'destroy']);

        Route::get('/dashboard/company/my-jobs/', [MyJobsController::class, 'myJobs']);
        Route::get('/dashboard/company/my-job-proposals/{id}', [MyJobsController::class, 'myJobProposals']);
        Route::get('/dashboard/company/rank-job-proposals/{id}', [MyJobsController::class, 'RankMyJobProposals']);
        Route::post('/dashboard/company/update-application-status/{id}', [MyJobsController::class, 'updateApplicationStatus']);

    });

    //------------------------------ Only User -----------------------------------------//

    Route::middleware(['auth:sanctum', 'role:user'])->group(function () {

        Route::post('/dashboard/company/apply-for-job', [JobApplyController::class, 'store']);
        Route::get('/dashboard/user/my-applies/', [JobApplyController::class, 'myProposals']);

    });

    //------------------------------ Chat -----------------------------------------//



    Route::middleware(['auth:sanctum'])->group(function () {

        Route::get('/chat/contacts/', [ChatController::class, 'getContacts']);
        Route::get('/chat/get-chat/{id}/', [ChatController::class, 'getChat']);
        Route::post('/chat/send-chat/{id}/', [ChatController::class, 'SendChat']);

    });

    Broadcast::routes(['middleware' => ['auth:sanctum']]);



    //------------------------------ notifications -----------------------------------------//

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)//                 ->middleware(['signed', 'throttle:6,1']);

// Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//                 ->middleware(['throttle:6,1']);
