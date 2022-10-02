<?php

use App\Http\Controllers\CollectionController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::inertia('sign-in', 'Auth/SignIn')->name('sign-in');
Route::inertia('create-account', 'Auth/CreateAccount')->name('create-account');
Route::inertia('terms-of-service', 'TermsAndConditions')->name('terms-of-service');
Route::inertia('privacy-policy', 'PrivacyPolicy')->name('privacy-policy');

Route::resource('collections.submissions', SubmissionController::class)->only(['show', 'update', 'favorite'])->middleware(['auth', 'verified']);
Route::resource('collections', CollectionController::class)->only(['index', 'store', 'show'])->middleware(['auth', 'verified']);

Route::post('submission/favorite/{submission}', [SubmissionController::class, 'favorite'])->middleware(['auth', 'verified'])->name('submission.favorite');
Route::post('submission/book/{submission}', [SubmissionController::class, 'book'])->middleware(['auth', 'verified'])->name('submission.book');
Route::put('submission/markViewed/{submission}', [SubmissionController::class, 'markViewed'])->middleware(['auth', 'verified'])->name('submission.markViewed');
Route::delete('submission/delete/{submission}', [SubmissionController::class, 'delete'])->middleware(['auth', 'verified'])->name('submission.delete');

Route::get('favorites', [SubmissionController::class, 'showFavorites'])->middleware(['auth', 'verified'])->name('favorites');
Route::get('inbox', [SubmissionController::class, 'inbox'])->middleware(['auth', 'verified'])->name('inbox');

require __DIR__.'/auth.php';
