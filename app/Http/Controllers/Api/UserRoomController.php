<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserRoomController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $rooms = Room::whereHas('hotel', fn($q) => $q->where('owner_id', $request->user()->id))
            ->with('hotel')->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => RoomResource::collection($rooms),
            'meta' => [
                'current_page' => $rooms->currentPage(),
                'last_page' => $rooms->lastPage(),
                'total' => $rooms->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:standard,superior,deluxe,suite,family,studio,apartment',
            'price_per_night' => 'required|numeric|min:0',
            'capacity_adults' => 'required|integer|min:1',
            'capacity_children' => 'nullable|integer|min:0',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('rooms', 'public');
            $validated['main_image'] = 'storage/' . $path;
        }

        $room = Room::create([
            ...$validated,
            'status' => 'pending',
            'is_active' => false,
            'is_available' => false,
            'quantity' => 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Квартира отправлена на модерацию!',
            'data' => new RoomResource($room),
        ], 201);
    }

    public function update(Request $request, Room $room): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:standard,superior,deluxe,suite,family,studio,apartment',
            'price_per_night' => 'required|numeric|min:0',
            'capacity_adults' => 'required|integer|min:1',
            'capacity_children' => 'nullable|integer|min:0',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('rooms', 'public');
            $validated['main_image'] = 'storage/' . $path;
        }

        $room->update([...$validated, 'status' => 'pending']);

        return response()->json([
            'success' => true,
            'message' => 'Квартира обновлена и отправлена на модерацию!',
            'data' => new RoomResource($room),
        ]);
    }

    public function destroy(Room $room): JsonResponse
    {
        $room->delete();
        return response()->json(['success' => true, 'message' => 'Квартира удалена']);
    }
}
