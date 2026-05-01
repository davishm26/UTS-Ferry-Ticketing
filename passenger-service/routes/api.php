<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PassengerController;

Route::get('/passengers', [PassengerController::class, 'index']);
Route::get('/passengers/{id}', [PassengerController::class, 'show']);
Route::post('/passengers', [PassengerController::class, 'store']);
Route::get('/passengers/{id}/tickets', [PassengerController::class, 'tickets']);
