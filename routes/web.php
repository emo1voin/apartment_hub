<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;

Route::get('/', function () {
    return redirect()->route('hotels.index');
});

// Debug route - удалить после тестирования
Route::get('/debug-auth', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user(),
        'session_id' => session()->getId(),
    ]);
});

// Test bookings route
Route::get('/test-bookings', function () {
    if (!auth()->check()) {
        return 'NOT AUTHENTICATED - Please login first';
    }
    return 'AUTHENTICATED as ' . auth()->user()->email . ' - Redirecting to bookings...';
})->middleware('auth');

// Test booking form
Route::get('/test-booking-form', function () {
    return view('test-booking');
})->middleware('auth')->name('test.booking.form');

// API Documentation route
Route::get('/api/documentation/json', function () {
    $path = storage_path('app/spectrum/openapi.json');
    if (!file_exists($path)) {
        return response()->json(['error' => 'Documentation not found. Run: php artisan spectrum:generate'], 404);
    }
    return response()->file($path, [
        'Content-Type' => 'application/json',
        'Access-Control-Allow-Origin' => '*'
    ]);
});

// Публичные маршруты для отелей
Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');

// Публичные маршруты для номеров
Route::get('/hotels/{hotel}/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

// Защищенные маршруты (требуют авторизации)
Route::middleware(['auth'])->group(function () {
    // Бронирования
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Отзывы
    Route::post('/hotels/{hotel}/reviews', [\App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [\App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Пользовательские дома и квартиры
    Route::prefix('my')->name('user.')->group(function () {
        Route::resource('hotels', \App\Http\Controllers\User\UserHotelController::class);
        Route::resource('rooms', \App\Http\Controllers\User\UserRoomController::class);
    });
});

// Админ маршруты
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Модерация
    Route::get('/moderation', [\App\Http\Controllers\Admin\ModerationController::class, 'index'])->name('moderation.index');
    Route::post('/moderation/hotels/{hotel}/approve', [\App\Http\Controllers\Admin\ModerationController::class, 'approveHotel'])->name('moderation.hotels.approve');
    Route::post('/moderation/hotels/{hotel}/reject', [\App\Http\Controllers\Admin\ModerationController::class, 'rejectHotel'])->name('moderation.hotels.reject');
    Route::post('/moderation/rooms/{room}/approve', [\App\Http\Controllers\Admin\ModerationController::class, 'approveRoom'])->name('moderation.rooms.approve');
    Route::post('/moderation/rooms/{room}/reject', [\App\Http\Controllers\Admin\ModerationController::class, 'rejectRoom'])->name('moderation.rooms.reject');

    // Управление отелями
    Route::get('/hotels/create', [HotelController::class, 'create'])->name('hotels.create');
    Route::post('/hotels', [HotelController::class, 'store'])->name('hotels.store');
    Route::get('/hotels/{hotel}/edit', [HotelController::class, 'edit'])->name('hotels.edit');
    Route::put('/hotels/{hotel}', [HotelController::class, 'update'])->name('hotels.update');
    Route::delete('/hotels/{hotel}', [HotelController::class, 'destroy'])->name('hotels.destroy');

    // Управление номерами
    Route::get('/hotels/{hotel}/rooms/create', [RoomController::class, 'create'])->name('rooms.create');
    Route::post('/hotels/{hotel}/rooms', [RoomController::class, 'store'])->name('rooms.store');
    Route::get('/rooms/{room}/edit', [RoomController::class, 'edit'])->name('rooms.edit');
    Route::put('/rooms/{room}', [RoomController::class, 'update'])->name('rooms.update');
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('rooms.destroy');
});

require __DIR__.'/auth.php';
