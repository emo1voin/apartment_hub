<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HotelResource;
use App\Models\Hotel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserHotelController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $hotels = Hotel::where('owner_id', $request->user()->id)->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => HotelResource::collection($hotels),
            'meta' => [
                'current_page' => $hotels->currentPage(),
                'last_page' => $hotels->lastPage(),
                'total' => $hotels->total(),
            ],
        ]);
    }

    public function show(Hotel $hotel): JsonResponse
    {
        return response()->json(['success' => true, 'data' => new HotelResource($hotel)]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'stars' => 'nullable|integer|min:1|max:5',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('hotels', 'public');
            $validated['main_image'] = 'storage/' . $path;
        }

        $hotel = Hotel::create([
            ...$validated,
            'owner_id' => $request->user()->id,
            'status' => 'pending',
            'is_active' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Дом отправлен на модерацию!',
            'data' => new HotelResource($hotel),
        ], 201);
    }

    public function update(Request $request, Hotel $hotel): JsonResponse
    {
        if ($hotel->owner_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Нет прав'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'stars' => 'nullable|integer|min:1|max:5',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('hotels', 'public');
            $validated['main_image'] = 'storage/' . $path;
        }

        $hotel->update([...$validated, 'status' => 'pending']);

        return response()->json([
            'success' => true,
            'message' => 'Дом обновлён и отправлен на модерацию!',
            'data' => new HotelResource($hotel),
        ]);
    }

    public function destroy(Request $request, Hotel $hotel): JsonResponse
    {
        if ($hotel->owner_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Нет прав'], 403);
        }

        $hotel->delete();
        return response()->json(['success' => true, 'message' => 'Дом удалён']);
    }
}
