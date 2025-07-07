<?php

use App\Livewire\QuotationTenderList;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::view('tender-settings', 'livewire/tender-settings')
    ->middleware(['auth'])
    ->name('tender-settings');

Route::get('list', QuotationTenderList::class)
    ->middleware(['auth'])
    ->name('list');

require __DIR__.'/auth.php';
