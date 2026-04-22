<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HotelResource;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Hotel::with('amenities');

        if ($request->has('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->has('country')) {
            $query->where('country', 'like', '%' . $request->country . '%');
        }

        if ($request->has('star_rating')) {
            $query->where('star_rating', $request->star_rating);
        }

        $hotels = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'data' => HotelResource::collection($hotels),
            'meta' => [
                'current_page' => $hotels->currentPage(),
                'last_page' => $hotels->lastPage(),
                'per_page' => $hotels->perPage(),
                'total' => $hotels->total(),
            ],
            'message' => 'Список отелей получен'
        ]);
    }

    public function show(Hotel $hotel): JsonResponse
    {
        $hotel->load(['amenities', 'rooms.amenities']);

        return response()->json([
            'success' => true,
            'data' => new HotelResource($hotel),
            'message' => 'Информация об отеле получена'
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
            'postal_code' => 'nullable|string',
            'phone' => 'required|string',
            'email' => 'required|email',
            'website' => 'nullable|url',
            'star_rating' => 'nullable|integer|min:1|max:5',
            'check_in_time' => 'required',
            'check_out_time' => 'required',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $hotel = Hotel::create($validated);

        return response()->json([
            'success' => true,
            'data' => new HotelResource($hotel),
            'message' => 'Отель успешно создан'
        ], 201);
    }

    public function update(Request $request, Hotel $hotel): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'address' => 'sometimes|string',
            'city' => 'sometimes|string',
            'country' => 'sometimes|string',
            'postal_code' => 'nullable|string',
            'phone' => 'sometimes|string',
            'email' => 'sometimes|email',
            'website' => 'nullable|url',
            'star_rating' => 'nullable|integer|min:1|max:5',
            'check_in_time' => 'sometimes',
            'check_out_time' => 'sometimes',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $hotel->update($validated);

        return response()->json([
            'success' => true,
            'data' => new HotelResource($hotel),
            'message' => 'Отель успешно обновлен'
        ]);
    }

    public function destroy(Hotel $hotel): JsonResponse
    {
        $hotel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Отель успешно удален'
        ]);
    }
}
