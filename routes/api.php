<?php

use App\Http\Controllers\Admin\UserController;
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
use App\Http\Controllers\Profile\ProfileController;
use \App\Http\Controllers\User\Jobs\JobApplyController;
use Illuminate\Support\Facades\Broadcast;

//------------------------------ Login system -----------------------------------------//


Route::middleware(['guest','api'])->group(function () {

    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

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

    Route::prefix('profile')->group(function () {

        Route::post('/update-name',[ProfileController::class, 'updateName']);
        Route::post('/update-password',[ProfileController::class, 'updatePassword']);
        Route::post('/update-profile-image',[ProfileController::class, 'updateImage']);
        Route::post('/update-profile-background-image',[ProfileController::class, 'updateBackgroundImage']);
        Route::post('/update-profile-background-image',[ProfileController::class, 'updateBackgroundImage']);
        Route::post('/update-profile-cv',[ProfileController::class, 'updateCV']);
        Route::post('/update-profile-description',[ProfileController::class, 'updateDescription']);
        Route::post('/update-profile-location',[ProfileController::class, 'updateLocationn']);
        Route::post('/update-skills-job',[AuthenticatedSessionController::class, 'updateSkillsAndJobs']);

    });
});
//------------------------------ Anyone -----------------------------------------//


    Route::get('/users', [UserController::class, 'getUsers']);
    Route::get('/companies', [UserController::class, 'getCompanies']);
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

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
//                 ->middleware(['signed', 'throttle:6,1']);

// Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//                 ->middleware(['throttle:6,1']);
