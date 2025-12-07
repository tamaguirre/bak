<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\EventTypeController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\RoomTypeController;
use App\Http\Controllers\Api\ShiftController;
use App\Http\Controllers\Api\ShiftTypeController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('rooms', RoomController::class);
    Route::get('room-types', [RoomTypeController::class, 'index']);
    Route::get('roles', [RoleController::class, 'index']);
    Route::apiResource('reservations', ReservationController::class);
    Route::get('shift-types', [ShiftTypeController::class, 'index']);
    Route::get('shifts', [ShiftController::class, 'index']);
    Route::post('shifts', [ShiftController::class, 'store']);
    Route::get('event-types', [EventTypeController::class, 'index']);
    Route::post('events', [EventController::class, 'store']);
});

