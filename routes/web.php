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

require __DIR__.'/auth.php';
