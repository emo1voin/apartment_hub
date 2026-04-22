<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

class UserRoomController extends Controller
{
    public function index()
    {
        $rooms = Room::whereHas('hotel', function($query) {
            $query->where('owner_id', auth()->id());
        })->with('hotel')->latest()->paginate(10);
        
        return view('user.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $hotels = Hotel::where('owner_id', auth()->id())
            ->where('status', 'approved')
            ->get();
        
        if ($hotels->isEmpty()) {
            return redirect()->route('user.hotels.index')
                ->with('error', 'Сначала создайте и одобрите дом!');
        }
        
        return view('user.rooms.create', compact('hotels'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|max:100',
            'capacity_adults' => 'required|integer|min:1',
            'capacity_children' => 'nullable|integer|min:0',
            'price_per_night' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        // Проверяем, что отель принадлежит пользователю
        $hotel = Hotel::where('id', $validated['hotel_id'])
            ->where('owner_id', auth()->id())
            ->firstOrFail();

        $room = Room::create([
            ...$validated,
            'total_capacity' => $validated['capacity_adults'] + ($validated['capacity_children'] ?? 0),
            'available_quantity' => $validated['quantity'],
            'status' => 'pending',
            'is_active' => false,
            'is_available' => false,
        ]);

        return redirect()->route('user.rooms.index')
            ->with('success', 'Квартира отправлена на модерацию!');
    }

    public function edit(Room $room)
    {
        // Проверяем, что комната принадлежит пользователю
        if ($room->hotel->owner_id !== auth()->id()) {
            abort(403);
        }
        
        $hotels = Hotel::where('owner_id', auth()->id())
            ->where('status', 'approved')
            ->get();
        
        return view('user.rooms.edit', compact('room', 'hotels'));
    }

    public function update(Request $request, Room $room)
    {
        // Проверяем, что комната принадлежит пользователю
        if ($room->hotel->owner_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|max:100',
            'capacity_adults' => 'required|integer|min:1',
            'capacity_children' => 'nullable|integer|min:0',
            'price_per_night' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $room->update([
            ...$validated,
            'total_capacity' => $validated['capacity_adults'] + ($validated['capacity_children'] ?? 0),
            'status' => 'pending', // Отправляем на повторную модерацию
        ]);

        return redirect()->route('user.rooms.index')
            ->with('success', 'Квартира обновлена и отправлена на модерацию!');
    }

    public function destroy(Room $room)
    {
        // Проверяем, что комната принадлежит пользователю
        if ($room->hotel->owner_id !== auth()->id()) {
            abort(403);
        }
        
        $room->delete();

        return redirect()->route('user.rooms.index')
            ->with('success', 'Квартира удалена.');
    }
}
