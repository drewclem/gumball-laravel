<?php

use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function() {
  Route::post('submission/favorite/{submission}', [SubmissionController::class, 'favorite'])->middleware(['auth', 'verified'])->name('submission.favorite');
  Route::post('submission/book/{submission}', [SubmissionController::class, 'book'])->middleware(['auth', 'verified'])->name('submission.book');
  Route::put('submission/markViewed/{submission}', [SubmissionController::class, 'markViewed'])->middleware(['auth', 'verified'])->name('submission.markViewed');
  Route::delete('submission/delete/{submission}', [SubmissionController::class, 'delete'])->middleware(['auth', 'verified'])->name('submission.delete');
});