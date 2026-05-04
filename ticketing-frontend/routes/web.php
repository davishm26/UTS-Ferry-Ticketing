<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index']);
Route::post('/buy', [DashboardController::class, 'buyTicket'])->name('buy.ticket');
Route::put('/tickets/{id}', [DashboardController::class, 'updateTicket'])->name('tickets.update');
Route::delete('/tickets/{id}', [DashboardController::class, 'deleteTicket'])->name('tickets.destroy');