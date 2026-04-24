<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Hotel $hotel): JsonResponse
    {
        // Проверяем, не оставлял ли пользователь уже отзыв
        $existing = Review::withTrashed()
            ->where('user_id', $request->user()->id)
            ->where('hotel_id', $hotel->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Вы уже оставляли отзыв на этот дом. Можно оставить только один отзыв.'
            ], 422);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:2000',
            'title' => 'nullable|string|max:255',
            'pros' => 'nullable|string|max:1000',
            'cons' => 'nullable|string|max:1000',
            'rating_cleanliness' => 'nullable|integer|min:1|max:5',
            'rating_comfort' => 'nullable|integer|min:1|max:5',
            'rating_location' => 'nullable|integer|min:1|max:5',
            'rating_service' => 'nullable|integer|min:1|max:5',
            'rating_value' => 'nullable|integer|min:1|max:5',
            'travel_type' => 'nullable|string',
            'is_recommended' => 'nullable|boolean',
        ]);

        $review = Review::create([
            'user_id' => $request->user()->id,
            'hotel_id' => $hotel->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'title' => $validated['title'] ?? null,
            'pros' => $validated['pros'] ?? null,
            'cons' => $validated['cons'] ?? null,
            'rating_cleanliness' => $validated['rating_cleanliness'] ?? null,
            'rating_comfort' => $validated['rating_comfort'] ?? null,
            'rating_location' => $validated['rating_location'] ?? null,
            'rating_service' => $validated['rating_service'] ?? null,
            'rating_value' => $validated['rating_value'] ?? null,
            'travel_type' => $validated['travel_type'] ?? null,
            'is_recommended' => $validated['is_recommended'] ?? false,
            'is_approved' => true,
        ]);

        // Обновляем рейтинг отеля
        $this->updateHotelRating($hotel);

        return response()->json([
            'success' => true,
            'message' => 'Отзыв добавлен',
            'data' => $review,
        ], 201);
    }

    private function updateHotelRating(Hotel $hotel): void
    {
        $reviews = $hotel->reviews()->where('is_approved', true);
        $count = $reviews->count();

        if ($count === 0) return;

        $hotel->update([
            'rating' => $reviews->avg('rating'),
            'review_count' => $count,
            'rating_cleanliness' => $reviews->avg('rating_cleanliness'),
            'rating_comfort' => $reviews->avg('rating_comfort'),
            'rating_location' => $reviews->avg('rating_location'),
            'rating_service' => $reviews->avg('rating_service'),
            'rating_value' => $reviews->avg('rating_value'),
        ]);
    }
}
