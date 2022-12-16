<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->group(function() {
  Route::get('settings', [RegisteredUserController::class, 'index'])->name('settings');
  Route::get('settings/edit', [RegisteredUserController::class, 'show'])->name('settings.show');
  Route::post('settings', [RegisteredUserController::class, 'edit'])->name('settings.edit');
  Route::post('settings/upload', [RegisteredUserController::class, 'uploadAvatar'])->name('settings.upload');
  Route::put('settings/set-view-mode', [RegisteredUserController::class, 'setViewMode'])->name('settings.view-mode');
});

