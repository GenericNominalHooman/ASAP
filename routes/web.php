<?php

use App\Livewire\QuotationTenderList;
use App\Livewire\TenderSettings;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('tender-settings', TenderSettings::class)
    ->middleware(['auth'])
    ->name('tender-settings');

Route::get('list', QuotationTenderList::class)
    ->middleware(['auth'])
    ->name('list');

Route::post('download/advert/{id}', [\App\Http\Controllers\DownloadController::class, 'downloadAdvert'])
    ->middleware(['auth'])
    ->name('download.advert');

Route::post('download/slip/{id}', [\App\Http\Controllers\DownloadController::class, 'downloadSlip'])
    ->middleware(['auth'])
    ->name('download.slip');

require __DIR__ . '/auth.php';
