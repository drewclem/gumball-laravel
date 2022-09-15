<?php

use App\Http\Controllers\CollectionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function() {
  Route::post('collection', [CollectionController::class, 'store'])->name('collection');
});