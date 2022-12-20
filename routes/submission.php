<?php

use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth', 'verified'])->group(function() {
  Route::put('submission/favorite/{submission}', [SubmissionController::class, 'favorite'])->name('submission.favorite');
  Route::put('submission/book/{submission}', [SubmissionController::class, 'book'])->name('submission.book');
  Route::put('submission/markViewed/{submission}', [SubmissionController::class, 'markViewed'])->name('submission.markViewed');
  Route::put('submission/like/{submission}', [SubmissionController::class, 'like'])->name('submission.like');
  Route::put('submission/dislike/{submission}', [SubmissionController::class, 'dislike'])->name('submission.dislike');
  Route::post('submission/decline/{submission}', [SubmissionController::class, 'decline'])->name('submission.decline');
  Route::delete('submission/delete/{submission}', [SubmissionController::class, 'delete'])->name('submission.delete');
});