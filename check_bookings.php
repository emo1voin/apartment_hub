<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$bookings = App\Models\Booking::with('hotel', 'room')->get();

echo "Total bookings: " . $bookings->count() . "\n\n";

foreach ($bookings as $booking) {
    echo "Booking ID: {$booking->id}\n";
    echo "  hotel_id: {$booking->hotel_id}\n";
    echo "  hotel exists: " . ($booking->hotel ? 'YES (id=' . $booking->hotel->id . ')' : 'NO') . "\n";
    echo "  room_id: {$booking->room_id}\n";
    echo "  room exists: " . ($booking->room ? 'YES (id=' . $booking->room->id . ')' : 'NO') . "\n";
    echo "\n";
}
