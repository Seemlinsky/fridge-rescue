<?php

use App\Http\Controllers\FridgeItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FridgeItemController::class, 'index']);

Route::post('/fridge', [FridgeItemController::class, 'store'])
    ->name('fridge.store');
