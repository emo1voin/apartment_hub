<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Hotel $hotel)
    {
        $rooms = $hotel->rooms()
            ->with('amenities')
            ->where('is_active', true)
            ->get();

        return view('rooms.index', compact('hotel', 'rooms'));
    }

    public function show(Room $room)
    {
        $room->load(['hotel', 'amenities']);

        return view('rooms.show', compact('room'));
    }

    public function create(Hotel $hotel)
    {
        return view('rooms.create', compact('hotel'));
    }

    public function store(Request $request, Hotel $hotel)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'type' => 'required|in:standard,superior,deluxe,suite',
                'price_per_night' => 'required|numeric|min:0',
                'capacity_adults' => 'required|integer|min:1',
                'capacity_children' => 'nullable|integer|min:0',
                'bed_type' => 'required|in:single,double,queen,king,twin',
                'bed_count' => 'required|integer|min:1',
                'size_sqm' => 'nullable|numeric|min:0',
                'available_quantity' => 'required|integer|min:1',
                'is_available' => 'nullable|boolean',
            ]);

            $validated['hotel_id'] = $hotel->id;
            $validated['is_available'] = $request->has('is_available');

            // Handle main image upload
            $mainImagePath = null;
            if ($request->hasFile('main_image')) {
                $mainImage = $request->file('main_image');
                $mainImageName = time() . '_' . str_replace(' ', '_', $mainImage->getClientOriginalName());
                $mainImage->move(public_path('images/rooms'), $mainImageName);
                $mainImagePath = 'images/rooms/' . $mainImageName;
            }

            // Create room
            $room = Room::create($validated);

            // Update image if uploaded
            if ($mainImagePath) {
                $room->main_image = $mainImagePath;
                $room->save();
            }

            return redirect()->route('hotels.show', $hotel)
                ->with('success', 'Квартира успешно создана');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ошибка при создании квартиры: ' . $e->getMessage());
        }
    }

    public function edit(Room $room)
    {
        return view('rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:standard,superior,deluxe,suite,family,studio,apartment',
            'capacity_adults' => 'required|integer|min:1',
            'capacity_children' => 'nullable|integer|min:0',
            'price_per_night' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
        ]);

        $validated['total_capacity'] = $validated['capacity_adults'] + ($validated['capacity_children'] ?? 0);

        $room->update($validated);

        return redirect()->route('rooms.show', $room)
            ->with('success', 'Квартира успешно обновлена');
    }

    public function destroy(Room $room)
    {
        if ($room->bookings()->whereIn('status', ['pending', 'confirmed'])->exists()) {
            return redirect()->back()
                ->with('error', 'Невозможно удалить квартиру с активными бронированиями');
        }

        $room->delete();

        return redirect()->route('hotels.show', $room->hotel_id)
            ->with('success', 'Квартира успешно удалена');
    }
}
