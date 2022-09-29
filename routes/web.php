<?php

use App\Http\Controllers\CollectionController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\Collection;

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

Route::resource('collections', CollectionController::class)->only(['index', 'store', 'show'])->middleware(['auth', 'verified']);
Route::resource('collections.submissions', SubmissionController::class)->only(['show'])->middleware(['auth', 'verified']);

require __DIR__.'/auth.php';
