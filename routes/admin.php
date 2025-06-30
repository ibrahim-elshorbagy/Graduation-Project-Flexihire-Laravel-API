<?php

use App\Livewire\Pages\Admin\ContactUs\ContactUsList;
use App\Livewire\Pages\Admin\Reports\ReportShow;
use App\Livewire\Pages\Admin\Reports\ReportsList;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/reports', ReportsList::class)->name('admin.reports');
    Route::get('/admin/reports/{id}', ReportShow::class)->name('admin.reports.show');
    
    // Contact Us Routes
    Route::get('/admin/contact-messages', ContactUsList::class)->name('admin.contact-messages');
});
