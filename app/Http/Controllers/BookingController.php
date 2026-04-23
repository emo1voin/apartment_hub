<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Services\ApiService;
use App\Services\AuthService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        private ApiService $api,
        private AuthService $auth
    ) {}

    public function index()
    {
        $userId = $this->auth->id();
        $bookings = \App\Models\Booking::where('user_id', $userId)
            ->with(['hotel', 'room'])
            ->whereHas('hotel')
            ->whereHas('room')
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        // Читаем напрямую из БД для совместимости с Blade шаблонами
        $booking = \App\Models\Booking::with(['hotel', 'room', 'user'])->findOrFail($id);
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
        $result = $this->api->post('bookings', [
            'room_id' => $request->room_id,
            'check_in' => $request->check_in,
            'check_out' => $request->check_out,
            'adults' => $request->adults,
            'children' => $request->children ?? 0,
            'special_requests' => $request->special_requests,
        ]);

        if (!empty($result['success']) && $result['success']) {
            $bookingId = $result['data']['id'] ?? null;
            if ($bookingId) {
                return redirect()->route('bookings.show', $bookingId)
                    ->with('success', 'Бронирование успешно создано!');
            }
            return redirect()->route('bookings.index')->with('success', 'Бронирование создано!');
        }

        return redirect()->back()->with('error', $result['message'] ?? 'Ошибка создания бронирования')->withInput();
    }

    public function cancel($id)
    {
        $result = $this->api->post("bookings/{$id}/cancel");

        if (!empty($result['success']) && $result['success']) {
            return redirect()->route('bookings.show', $id)->with('success', 'Бронирование отменено');
        }

        return redirect()->back()->with('error', $result['message'] ?? 'Ошибка отмены');
    }
}
