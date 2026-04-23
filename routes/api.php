<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HotelController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\BookingController;

Route::prefix('v1')->group(function () {

    // Ping
    Route::get('/ping', function () {
        return response()->json(['success' => true, 'message' => 'API is working!', 'timestamp' => now()]);
    });

    // Публичные маршруты
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::post('/auth/login', [AuthController::class, 'login']);

    Route::get('/hotels', [HotelController::class, 'index']);
    Route::get('/hotels/{hotel}', [HotelController::class, 'show']);
    Route::get('/rooms', [RoomController::class, 'index']);
    Route::get('/rooms/{room}', [RoomController::class, 'show']);

    // Защищённые маршруты
    Route::middleware('auth:sanctum')->group(function () {
        // Auth
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
        Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('api.bookings.cancel');

        // Отзывы
        Route::post('/hotels/{hotel}/reviews', [\App\Http\Controllers\Api\ReviewController::class, 'store'])->name('api.reviews.store');

        // Пользовательские дома и квартиры
        Route::get('/my/hotels', [\App\Http\Controllers\Api\UserHotelController::class, 'index'])->name('api.user.hotels.index');
        Route::post('/my/hotels', [\App\Http\Controllers\Api\UserHotelController::class, 'store'])->name('api.user.hotels.store');
        Route::get('/my/hotels/{hotel}', [\App\Http\Controllers\Api\UserHotelController::class, 'show'])->name('api.user.hotels.show');
        Route::put('/my/hotels/{hotel}', [\App\Http\Controllers\Api\UserHotelController::class, 'update'])->name('api.user.hotels.update');
        Route::delete('/my/hotels/{hotel}', [\App\Http\Controllers\Api\UserHotelController::class, 'destroy'])->name('api.user.hotels.destroy');

        Route::get('/my/rooms', [\App\Http\Controllers\Api\UserRoomController::class, 'index'])->name('api.user.rooms.index');
        Route::post('/my/rooms', [\App\Http\Controllers\Api\UserRoomController::class, 'store'])->name('api.user.rooms.store');
        Route::put('/my/rooms/{room}', [\App\Http\Controllers\Api\UserRoomController::class, 'update'])->name('api.user.rooms.update');
        Route::delete('/my/rooms/{room}', [\App\Http\Controllers\Api\UserRoomController::class, 'destroy'])->name('api.user.rooms.destroy');

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

            // Модерация
            Route::get('/moderation', [\App\Http\Controllers\Api\ModerationController::class, 'index'])->name('api.moderation.index');
            Route::post('/moderation/hotels/{hotel}/approve', [\App\Http\Controllers\Api\ModerationController::class, 'approveHotel'])->name('api.moderation.hotels.approve');
            Route::post('/moderation/hotels/{hotel}/reject', [\App\Http\Controllers\Api\ModerationController::class, 'rejectHotel'])->name('api.moderation.hotels.reject');
            Route::post('/moderation/rooms/{room}/approve', [\App\Http\Controllers\Api\ModerationController::class, 'approveRoom'])->name('api.moderation.rooms.approve');
            Route::post('/moderation/rooms/{room}/reject', [\App\Http\Controllers\Api\ModerationController::class, 'rejectRoom'])->name('api.moderation.rooms.reject');
        });
    });
});
