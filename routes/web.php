<?php

use App\Http\Controllers\LiveFormController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\TagRelationController;
use App\Http\Controllers\Auth\RegisteredUserController;
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

Route::get('favorites', [SubmissionController::class, 'showFavorites'])->middleware(['auth', 'verified'])->name('favorites');
Route::get('inbox', [SubmissionController::class, 'inbox'])->middleware(['auth', 'verified'])->name('inbox');

Route::get('tags', [TagController::class, 'index'])->middleware(['auth', 'verified'])->name('tags');
Route::put('tags', [TagController::class, 'store'])->middleware(['auth', 'verified'])->name('tags.store');
Route::delete('tags', [TagController::class, 'delete'])->middleware(['auth', 'verified'])->name('tags.delete');
Route::put('tag_relation', [TagRelationController::class, 'store'])->middleware(['auth', 'verified'])->name('tag_relation.store');
Route::delete('tag_relation', [TagRelationController::class, 'delete'])->middleware(['auth', 'verified'])->name('tag_relation.delete');
Route::get('settings', [RegisteredUserController::class, 'index'])->middleware(['auth', 'verified'])->name('settings');
Route::get('settings/edit', [RegisteredUserController::class, 'show'])->middleware(['auth', 'verified'])->name('settings.show');
Route::post('settings', [RegisteredUserController::class, 'edit'])->middleware(['auth', 'verified'])->name('settings.edit');
Route::post('settings/upload', [RegisteredUserController::class, 'uploadAvatar'])->middleware(['auth', 'verified'])->name('settings.upload');

require __DIR__.'/submission.php';
require __DIR__.'/collection.php';
require __DIR__.'/auth.php';

Route::get('/{username}', [LiveFormController::class, 'index']);
Route::post('submission', [SubmissionController::class, 'store'])->name('submission.store');
