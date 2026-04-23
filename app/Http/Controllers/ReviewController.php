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
        ]);

        if (!empty($result['success'])) {
            return redirect()->back()->with('success', 'Отзыв добавлен!');
        }

        return redirect()->back()->with('error', $result['message'] ?? 'Ошибка добавления отзыва');
    }

    public function update(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->back()->with('success', 'Отзыв обновлён!');
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->back()->with('success', 'Отзыв удалён!');
    }
}
