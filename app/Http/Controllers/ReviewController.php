<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Services\ApiService;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(private ApiService $api) {}

    public function store(Request $request, $hotelId)
    {
        $result = $this->api->post("hotels/{$hotelId}/reviews", [
            'rating' => $request->rating,
            'comment' => $request->comment,
            'title' => $request->title,
            'pros' => $request->pros,
            'cons' => $request->cons,
            'rating_cleanliness' => $request->rating_cleanliness,
            'rating_comfort' => $request->rating_comfort,
            'rating_location' => $request->rating_location,
            'rating_service' => $request->rating_service,
            'rating_value' => $request->rating_value,
            'travel_type' => $request->travel_type,
            'is_recommended' => $request->has('is_recommended') ? 1 : 0,
        ]);

        if (!empty($result['success'])) {
            return redirect()->back()->with('success', 'Отзыв добавлен!');
        }

        return redirect()->back()->with('error', $result['message'] ?? 'Ошибка добавления отзыва');
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $review->update($request->only(['rating', 'comment', 'title']));
        return redirect()->back()->with('success', 'Отзыв обновлён!');
    }

    public function destroy($id)
    {
        Review::withTrashed()->where('id', $id)->forceDelete();
        return redirect()->back()->with('success', 'Отзыв удалён!');
    }
}
