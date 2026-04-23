<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $query = Hotel::approved()->where('is_active', true)->with('amenities');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('country', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->filled('stars')) {
            $query->where('stars', $request->stars);
        }

        $hotels = $query->latest()->paginate(12);

        return view('hotels.index', compact('hotels'));
    }

    public function show($id)
    {
        $hotel = Hotel::with(['rooms' => function ($q) {
            $q->approved()->where('is_active', true);
        }, 'amenities', 'reviews.user'])->findOrFail($id);

        return view('hotels.show', compact('hotel'));
    }
}
