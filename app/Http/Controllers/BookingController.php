<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    public function index()
    {
        try {
            // Debug logging
            \Log::info('BookingController@index called', [
                'user_id' => auth()->id(),
                'authenticated' => auth()->check(),
            ]);

            $bookings = auth()->user()->bookings()
                ->with(['hotel', 'room'])
                ->whereHas('hotel')
                ->whereHas('room')
                ->latest()
                ->paginate(10);

            \Log::info('Bookings loaded successfully', [
                'count' => $bookings->count(),
            ]);

            return view('bookings.index', compact('bookings'));
        } catch (\Exception $e) {
            \Log::error('Error in BookingController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return redirect()->route('hotels.index')
                ->with('error', 'Произошла ошибка при загрузке бронирований: ' . $e->getMessage());
        }
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->load(['hotel', 'room', 'user']);

        return view('bookings.show', compact('booking'));
    }

    public function create(Request $request)
    {
        $room = Room::with('hotel')->findOrFail($request->room_id);

        $checkIn = $request->check_in;
        $checkOut = $request->check_out;
        $adults = $request->adults ?? 1;
        $children = $request->children ?? 0;

        if (!$checkIn || !$checkOut) {
            return redirect()->back()->with('error', 'Укажите даты заезда и выезда');
        }

        $nights = (strtotime($checkOut) - strtotime($checkIn)) / 86400;
        $subtotal = $room->price_per_night * $nights;
        $taxAmount = $subtotal * 0.1;
        $serviceFee = $subtotal * 0.05;
        $totalPrice = $subtotal + $taxAmount + $serviceFee;

        return view('bookings.create', compact(
            'room', 'checkIn', 'checkOut', 'adults', 'children',
            'nights', 'subtotal', 'taxAmount', 'serviceFee', 'totalPrice'
        ));
    }

    public function store(Request $request)
    {
        \Log::info('=== BOOKING STORE START ===');
        \Log::info('User ID: ' . auth()->id());
        \Log::info('User email: ' . auth()->user()->email);
        \Log::info('Request method: ' . $request->method());
        \Log::info('Request URL: ' . $request->fullUrl());
        \Log::info('Request data:', $request->all());
        \Log::info('Request headers:', $request->headers->all());
        
        try {
            $validated = $request->validate([
                'room_id' => 'required|exists:rooms,id',
                'check_in' => 'required|date',
                'check_out' => 'required|date|after:check_in',
                'adults' => 'required|integer|min:1',
                'children' => 'nullable|integer|min:0',
                'special_requests' => 'nullable|string',
            ]);
            
            \Log::info('Validation passed', $validated);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', $e->errors());
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Пожалуйста, проверьте введённые данные');
        } catch (\Exception $e) {
            \Log::error('Unexpected error during validation:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Произошла ошибка: ' . $e->getMessage());
        }

        $room = Room::findOrFail($validated['room_id']);
        \Log::info('Room found: ' . $room->id . ' - ' . $room->name);

        if (!$room->isAvailable($validated['check_in'], $validated['check_out'])) {
            \Log::warning('Room not available for dates: ' . $validated['check_in'] . ' to ' . $validated['check_out']);
            return redirect()->back()->with('error', 'Номер недоступен на выбранные даты');
        }

        $nights = (strtotime($validated['check_out']) - strtotime($validated['check_in'])) / 86400;
        $subtotal = $room->price_per_night * $nights;
        $taxAmount = $subtotal * 0.1;
        $serviceFee = $subtotal * 0.05;
        $totalPrice = $subtotal + $taxAmount + $serviceFee;

        \Log::info('Calculated prices:', [
            'nights' => $nights,
            'subtotal' => $subtotal,
            'tax' => $taxAmount,
            'service_fee' => $serviceFee,
            'total' => $totalPrice
        ]);

        $booking = Booking::create([
            'booking_number' => 'BKG-' . strtoupper(Str::random(8)),
            'user_id' => auth()->id(),
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

        \Log::info('Booking created successfully:', [
            'id' => $booking->id,
            'booking_number' => $booking->booking_number
        ]);
        \Log::info('Redirecting to: bookings.show with ID ' . $booking->id);
        \Log::info('Redirect URL: ' . route('bookings.show', $booking));

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Бронирование успешно создано! Номер бронирования: ' . $booking->booking_number);
    }

    public function cancel(Booking $booking)
    {
        $this->authorize('update', $booking);

        if (!$booking->canBeCancelled()) {
            return redirect()->back()->with('error', 'Невозможно отменить это бронирование');
        }

        $booking->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Бронирование отменено');
    }
}
