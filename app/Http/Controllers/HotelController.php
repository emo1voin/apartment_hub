<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index(Request $request)
    {
        $query = Hotel::with(['amenities', 'rooms'])
            ->where('is_active', true)
            ->where('status', 'approved'); // Показываем только одобренные

        if ($request->has('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->has('country')) {
            $query->where('country', $request->country);
        }

        if ($request->has('stars')) {
            $query->where('stars', $request->stars);
        }

        if ($request->has('min_price')) {
            $query->where('min_price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('max_price', '<=', $request->max_price);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('city', 'like', '%' . $search . '%');
            });
        }

        $hotels = $query->paginate(12);

        return view('hotels.index', compact('hotels'));
    }

    public function show(Hotel $hotel)
    {
        $hotel->load(['amenities', 'rooms.amenities', 'reviews.user']);

        return view('hotels.show', compact('hotel'));
    }

    public function create()
    {
        return view('hotels.create');
    }

    public function store(Request $request)
    {
        try {
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
                'latitude' => 'nullable|numeric|min:-90|max:90',
                'longitude' => 'nullable|numeric|min:-180|max:180',
            ]);

            // Rename star_rating to stars for database
            if (isset($validated['star_rating'])) {
                $validated['stars'] = $validated['star_rating'];
                unset($validated['star_rating']);
            }

            // Handle main image upload
            $mainImagePath = null;
            if ($request->hasFile('main_image')) {
                $mainImage = $request->file('main_image');
                $mainImageName = time() . '_' . str_replace(' ', '_', $mainImage->getClientOriginalName());
                $mainImage->move(public_path('images/hotels'), $mainImageName);
                $mainImagePath = 'images/hotels/' . $mainImageName;
            }

            // Create hotel
            $hotel = Hotel::create($validated);

            // Update image if uploaded
            if ($mainImagePath) {
                $hotel->main_image = $mainImagePath;
                $hotel->save();
            }

            return redirect()->route('hotels.show', $hotel)
                ->with('success', 'Дом успешно создан');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ошибка при создании дома: ' . $e->getMessage());
        }
    }

    public function edit(Hotel $hotel)
    {
        return view('hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'stars' => 'required|integer|min:1|max:5',
        ]);

        $hotel->update($validated);

        return redirect()->route('hotels.show', $hotel)
            ->with('success', 'Дом успешно обновлен');
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->delete();

        return redirect()->route('hotels.index')
            ->with('success', 'Дом успешно удален');
    }
}
