<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\TicketResponseController;
use App\Http\Controllers\Api\CategoryController;

/*
|--------------------------------------------------------------------------
| Public routes (no auth required)
|--------------------------------------------------------------------------
*/

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// List active categories (needed on the registration/submission page before login)
Route::get('/categories', [CategoryController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Authenticated routes (all roles: public, student, staff, admin)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me',      [AuthController::class, 'me']);

    // Any logged-in user can submit a ticket
    Route::post('/tickets', [TicketController::class, 'store']);

    // Track own ticket by ticket_number
    Route::get('/tickets/track/{ticket_number}', [TicketController::class, 'track']);

    // View own submitted tickets
    Route::get('/tickets/my', [TicketController::class, 'myTickets']);

    // Reply to own ticket
    Route::post('/tickets/{ticket}/responses', [TicketResponseController::class, 'store']);

    /*
    |----------------------------------------------------------------------
    | Staff & Admin routes
    |----------------------------------------------------------------------
    */
    Route::middleware('role:staff,admin')->group(function () {

        Route::get('/tickets',          [TicketController::class, 'index']);
        Route::get('/tickets/{ticket}', [TicketController::class, 'show']);
        Route::patch('/tickets/{ticket}', [TicketController::class, 'update']);
        Route::post('/tickets/{ticket}/resolve', [TicketController::class, 'resolve']);

        Route::get('/tickets/{ticket}/responses', [TicketResponseController::class, 'index']);
        Route::post('/tickets/{ticket}/responses/staff', [TicketResponseController::class, 'storeStaff']);
    });

    /*
    |----------------------------------------------------------------------
    | Admin-only routes
    |----------------------------------------------------------------------
    */
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('/categories', CategoryController::class)->except(['index']);
        Route::delete('/tickets/{ticket}', [TicketController::class, 'destroy']);
        Route::get('/stats', [TicketController::class, 'stats']);
    });
});