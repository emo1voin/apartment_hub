<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\BookingController;

Route::prefix('v1')->group(function () {
    
    // Ping endpoint
    Route::get('/ping', function() {
        return response()->json([
            'success' => true,
            'message' => 'API is working!',
            'timestamp' => now()
        ]);
    });

    // Публичные маршруты
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    // Публичные маршруты для отелей и номеров
    Route::get('/hotels', [HotelController::class, 'index']);
    Route::get('/hotels/{hotel}', [HotelController::class, 'show']);
    Route::get('/rooms', [RoomController::class, 'index']);
    Route::get('/rooms/{room}', [RoomController::class, 'show']);

    // Защищенные маршруты (требуют авторизации)
    Route::middleware('auth:sanctum')->group(function () {
        // Аутентификация
        Route::get('/auth/user', [AuthController::class, 'user']);
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/refresh', [AuthController::class, 'refresh']);

        // Бронирования
        Route::apiResource('bookings', BookingController::class)->names([
            'index' => 'api.bookings.index',
            'store' => 'api.bookings.store',
            'show' => 'api.bookings.show',
            'update' => 'api.bookings.update',
            'destroy' => 'api.bookings.destroy',
        ]);

        // Админ маршруты
        Route::middleware('can:admin')->group(function () {
            Route::apiResource('hotels', HotelController::class)
                ->except(['index', 'show'])
                ->names([
                    'store' => 'api.hotels.store',
                    'update' => 'api.hotels.update',
                    'destroy' => 'api.hotels.destroy',
                ]);
            Route::apiResource('rooms', RoomController::class)
                ->except(['index', 'show'])
                ->names([
                    'store' => 'api.rooms.store',
                    'update' => 'api.rooms.update',
                    'destroy' => 'api.rooms.destroy',
                ]);
        });
    });
});
