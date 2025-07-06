<?php

use App\Livewire\Pages\Admin\Reports\ReportsList;
use App\Livewire\Pages\Admin\Reports\ReportShow;
use App\Livewire\Pages\Admin\UserManagement\UsersList;
use App\Livewire\Pages\Admin\UserManagement\UsersForm;
use App\Livewire\Pages\Admin\UserManagement\UserProfile;
use App\Livewire\Admin\Dashboard;
use Illuminate\Support\Facades\Route;

use App\Livewire\Pages\Admin\ContactUs\ContactUsList;


Route::middleware(['auth', 'verified','role:admin'])->get('admin/dashboard', Dashboard::class)->name('dashboard');

Route::middleware(['auth'])->get('admin/profile', function () {
    return view('profile');
})->name('profile');

// Admin Routes
Route::middleware(['auth', 'verified','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    
    // User Management
    Route::get('/users', UsersList::class)->name('users');
    Route::get('/users/create', UsersForm::class)->name('users.create');
    Route::get('/users/{userId}/edit', UsersForm::class)->name('users.edit');
    Route::get('/users/{userId}/profile', UserProfile::class)->name('users.profile');

    // Report Management
    Route::get('/reports', ReportsList::class)->name('reports');
    Route::get('/reports/{id}', ReportShow::class)->name('reports.show');

    Route::get('/contact-messages', ContactUsList::class)->name('contact-messages');

});


require __DIR__.'/auth.php';
