<?php

use App\Http\Controllers\CollectionController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function() {
  Route::resource('collections.submissions', SubmissionController::class)->only(['show', 'update', 'favorite']);
  Route::resource('collections', CollectionController::class)->only(['index', 'store', 'show', 'destroy']);
  Route::put('collections/{collection}', [CollectionController::class, 'close'])->name('collections.close');
});