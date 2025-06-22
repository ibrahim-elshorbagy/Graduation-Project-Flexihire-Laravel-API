<?php

use App\Livewire\Pages\Admin\UserManagement\UsersList;
use App\Livewire\Pages\Admin\UserManagement\UsersForm;
use Illuminate\Support\Facades\Route;


Route::redirect('/', 'dashboard');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Admin Routes
Route::middleware(['auth', 'verified','role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // User Management
    Route::get('/users', UsersList::class)->name('users');
    Route::get('/users/create', UsersForm::class)->name('users.create');
    Route::get('/users/{userId}/edit', UsersForm::class)->name('users.edit');


    
});

require __DIR__.'/auth.php';
