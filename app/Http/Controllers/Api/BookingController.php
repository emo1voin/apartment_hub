<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = $request->user()->bookings()->with(['hotel', 'room']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => BookingResource::collection($bookings),
            'meta' => [
                'current_page' => $bookings->currentPage(),
                'last_page' => $bookings->lastPage(),
                'per_page' => $bookings->perPage(),
                'total' => $bookings->total(),
            ],
            'message' => 'Список бронирований получен'
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'check_in' => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in',
            'adults' => 'required|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'special_requests' => 'nullable|string',
        ]);

        $room = Room::findOrFail($validated['room_id']);

        if (!$room->isAvailable($validated['check_in'], $validated['check_out'])) {
            return response()->json([
                'success' => false,
                'message' => 'Номер недоступен на выбранные даты'
            ], 400);
        }

        $nights = (strtotime($validated['check_out']) - strtotime($validated['check_in'])) / 86400;
        $subtotal = $room->price_per_night * $nights;
        $taxAmount = $subtotal * 0.1;
        $serviceFee = $subtotal * 0.05;
        $totalPrice = $subtotal + $taxAmount + $serviceFee;

        $booking = Booking::create([
            'booking_number' => 'BKG-' . strtoupper(Str::random(8)),
            'user_id' => $request->user()->id,
            'hotel_id' => $room->hotel_id,
            'room_id' => $room->id,
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'nights' => $nights,
            'adults' => $validated['adults'],
            'children' => $validated['children'] ?? 0,
            'total_guests' => $validated['adults'] + ($validated['children'] ?? 0),
            'price_per_night' => $room->price_per_night,
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'service_fee' => $serviceFee,
            'total_price' => $totalPrice,
            'special_requests' => $validated['special_requests'] ?? null,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Бронирование успешно создано',
            'data' => new BookingResource($booking->load(['hotel', 'room']))
        ], 201);
    }

    public function show(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет прав для просмотра этого бронирования'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new BookingResource($booking->load(['hotel', 'room', 'user'])),
            'message' => 'Детали бронирования получены'
        ]);
    }

    public function update(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет прав для изменения этого бронирования'
            ], 403);
        }

        $validated = $request->validate([
            'special_requests' => 'nullable|string',
        ]);

        $booking->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Бронирование обновлено',
            'data' => new BookingResource($booking)
        ]);
    }

    public function destroy(Request $request, Booking $booking): JsonResponse
    {
        if ($booking->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет прав для отмены этого бронирования'
            ], 403);
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => 'Бронирование нельзя отменить в текущем статусе'
            ], 400);
        }

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $request->input('reason', 'Отмена пользователем'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Бронирование отменено'
        ]);
    }
}
