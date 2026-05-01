<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;

Route::get('/tickets', [TicketController::class, 'index']);
Route::get('/tickets/{id}', [TicketController::class, 'show']);
Route::get('/tickets/booking/{code}', [TicketController::class, 'showByCode']);
Route::post('/tickets', [TicketController::class, 'store']);
Route::put('/tickets/{id}/cancel', [TicketController::class, 'cancel']);
