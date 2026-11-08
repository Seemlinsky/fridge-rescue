<?php

use App\Http\Controllers\FridgeItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', [FridgeItemController::class, 'index'])
    ->name('fridge.index');

Route::post('/fridge', [FridgeItemController::class, 'store'])
    ->name('fridge.store');

Route::delete('/fridge/{fridgeItem}', [FridgeItemController::class, 'destroy'])
    ->name('fridge.destroy');
