<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;

use App\Http\Controllers\Auth\SignUpWith\GoogleContoller;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\RoadmapController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\OrderItemController;


//------------------------------ Login system -----------------------------------------//


Route::middleware(['guest','api'])->group(function () {

    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store']);

    Route::post('/reset-password', [NewPasswordController::class, 'store']);


    // Login with Google
    Route::get('/login-with-google',[GoogleContoller::class, 'redirectToGoogle']);
    Route::get('/google-callback',[GoogleContoller::class, 'handleGoogleCallback']);

});

//------------------------------ Profile -----------------------------------------//

Route::middleware(['auth:sanctum','role:user'])->group(function () {

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy']);

    Route::prefix('profile')->group(function () {

        Route::post('/update-name',[ProfileController::class, 'updateName']);
        Route::post('/update-password',[ProfileController::class, 'updatePassword']);

    });
});

//------------------------------ Admin -----------------------------------------//

Route::middleware(['auth:sanctum','role:admin'])->group(function () {

    Route::prefix('admin')->group(function () {

    Route::middleware(['role:admin'])->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('portfolios', PortfolioController::class);
        Route::resource('jobs', JobController::class);
        Route::resource('roadmaps', RoadmapController::class);
        Route::resource('courses', CourseController::class);
        Route::resource('products', ProductController::class);

    });

    });
});
Route::middleware(['auth:sanctum','role:admin'])->group(function () {


    Route::prefix('user')->group(function () {

    Route::middleware(['role:admin'])->group(function () {

        Route::resource('applications', ApplicationController::class);
        Route::resource('projects', ProjectController::class);
        Route::resource('order-details', OrderDetailController::class);
        Route::resource('order-items', OrderItemController::class);
    });

    });
});

// Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
//                 ->middleware(['signed', 'throttle:6,1']);

// Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//                 ->middleware(['throttle:6,1']);
