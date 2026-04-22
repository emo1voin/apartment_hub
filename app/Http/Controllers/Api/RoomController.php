<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\RoomResource;
use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Room::with(['hotel', 'amenities'])->where('is_available', true);

        if ($request->has('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('max_price')) {
            $query->where('price_per_night', '<=', $request->max_price);
        }

        $rooms = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => RoomResource::collection($rooms),
            'meta' => [
                'current_page' => $rooms->currentPage(),
                'last_page' => $rooms->lastPage(),
                'per_page' => $rooms->perPage(),
                'total' => $rooms->total(),
            ],
            'message' => 'Список номеров получен'
        ]);
    }

    public function show(Room $room): JsonResponse
    {
        $room->load(['hotel', 'amenities']);

        return response()->json([
            'success' => true,
            'data' => new RoomResource($room),
            'message' => 'Информация о номере получена'
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:standard,superior,deluxe,suite',
            'price_per_night' => 'required|numeric|min:0',
            'capacity_adults' => 'required|integer|min:1',
            'capacity_children' => 'required|integer|min:0',
            'bed_type' => 'required|in:single,double,queen,king,twin',
            'bed_count' => 'required|integer|min:1',
            'size_sqm' => 'nullable|numeric',
            'is_available' => 'boolean',
            'available_quantity' => 'required|integer|min:0',
        ]);

        $room = Room::create($validated);

        return response()->json([
            'success' => true,
            'data' => new RoomResource($room->load('hotel')),
            'message' => 'Номер успешно создан'
        ], 201);
    }

    public function update(Request $request, Room $room): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|in:standard,superior,deluxe,suite',
            'price_per_night' => 'sometimes|numeric|min:0',
            'capacity_adults' => 'sometimes|integer|min:1',
            'capacity_children' => 'sometimes|integer|min:0',
            'bed_type' => 'sometimes|in:single,double,queen,king,twin',
            'bed_count' => 'sometimes|integer|min:1',
            'size_sqm' => 'nullable|numeric',
            'is_available' => 'boolean',
            'available_quantity' => 'sometimes|integer|min:0',
        ]);

        $room->update($validated);

        return response()->json([
            'success' => true,
            'data' => new RoomResource($room->load('hotel')),
            'message' => 'Номер успешно обновлен'
        ]);
    }

    public function destroy(Room $room): JsonResponse
    {
        $room->delete();

        return response()->json([
            'success' => true,
            'message' => 'Номер успешно удален'
        ]);
    }
}
