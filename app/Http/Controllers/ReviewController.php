<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Hotel;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'rating_cleanliness' => 'nullable|integer|min:1|max:5',
            'rating_comfort' => 'nullable|integer|min:1|max:5',
            'rating_location' => 'nullable|integer|min:1|max:5',
            'rating_service' => 'nullable|integer|min:1|max:5',
            'rating_value' => 'nullable|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|min:10',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
            'travel_type' => 'nullable|in:alone,couple,family,friends,business',
            'is_recommended' => 'nullable|boolean',
        ]);

        // Проверяем, что пользователь бронировал этот отель
        $hasBooking = auth()->user()->bookings()
            ->where('hotel_id', $hotel->id)
            ->whereIn('status', ['completed', 'confirmed'])
            ->exists();

        $validated['user_id'] = auth()->id();
        $validated['hotel_id'] = $hotel->id;
        $validated['is_verified'] = $hasBooking;
        $validated['is_approved'] = true; // Автоматическое одобрение
        $validated['is_recommended'] = $request->has('is_recommended');

        $review = Review::create($validated);

        // Обновляем рейтинг отеля
        $this->updateHotelRating($hotel);

        return redirect()->route('hotels.show', $hotel)
            ->with('success', 'Спасибо за ваш отзыв!');
    }

    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403, 'У вас нет прав для редактирования этого отзыва');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'rating_cleanliness' => 'nullable|integer|min:1|max:5',
            'rating_comfort' => 'nullable|integer|min:1|max:5',
            'rating_location' => 'nullable|integer|min:1|max:5',
            'rating_service' => 'nullable|integer|min:1|max:5',
            'rating_value' => 'nullable|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'comment' => 'required|string|min:10',
            'pros' => 'nullable|string',
            'cons' => 'nullable|string',
            'travel_type' => 'nullable|in:alone,couple,family,friends,business',
            'is_recommended' => 'nullable|boolean',
        ]);

        $validated['is_recommended'] = $request->has('is_recommended');

        $review->update($validated);

        // Обновляем рейтинг отеля
        $this->updateHotelRating($review->hotel);

        return redirect()->route('hotels.show', $review->hotel)
            ->with('success', 'Отзыв успешно обновлен');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'У вас нет прав для удаления этого отзыва');
        }

        $hotel = $review->hotel;
        $review->delete();

        // Обновляем рейтинг отеля
        $this->updateHotelRating($hotel);

        return redirect()->route('hotels.show', $hotel)
            ->with('success', 'Отзыв удален');
    }

    private function updateHotelRating(Hotel $hotel)
    {
        $reviews = $hotel->reviews()->where('is_approved', true)->get();

        if ($reviews->count() > 0) {
            $hotel->rating = $reviews->avg('rating');
            $hotel->review_count = $reviews->count();
            $hotel->rating_cleanliness = $reviews->avg('rating_cleanliness');
            $hotel->rating_comfort = $reviews->avg('rating_comfort');
            $hotel->rating_location = $reviews->avg('rating_location');
            $hotel->rating_service = $reviews->avg('rating_service');
            $hotel->rating_value = $reviews->avg('rating_value');
            $hotel->save();
        }
    }
}
