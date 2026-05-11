<?php

namespace App\Http\Controllers;

use App\Models\Hotel;
use App\Services\ApiService;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function __construct(private ApiService $api) {}

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
        $hotel = Hotel::with(['amenities', 'reviews.user'])->findOrFail($id);
        $rooms = $hotel->rooms()->approved()->where('is_active', true)->paginate(12);

        return view('hotels.show', compact('hotel', 'rooms'));
    }

    public function create()
    {
        return view('hotels.create');
    }

    public function store(Request $request)
    {
        $data = $request->only(['name', 'description', 'short_description', 'address', 'city', 'country', 'phone', 'email', 'stars']);
        $hasFile = $request->hasFile('main_image');
        if ($hasFile) $data['main_image'] = $request->file('main_image');

        $result = $this->api->post('hotels', $data, $hasFile);

        if (!empty($result['success'])) {
            return redirect()->route('hotels.index')->with('success', 'Дом создан!');
        }
        return redirect()->back()->with('error', $result['message'] ?? 'Ошибка создания')->withInput();
    }

    public function edit($id)
    {
        $hotel = Hotel::with('amenities')->findOrFail($id);
        return view('hotels.edit', compact('hotel'));
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['name', 'description', 'short_description', 'address', 'city', 'country', 'phone', 'email', 'stars']);
        $hasFile = $request->hasFile('main_image');
        if ($hasFile) $data['main_image'] = $request->file('main_image');

        $result = $this->api->put("hotels/{$id}", $data, $hasFile);

        if (!empty($result['success'])) {
            return redirect()->route('hotels.index')->with('success', 'Дом обновлён!');
        }
        return redirect()->back()->with('error', $result['message'] ?? 'Ошибка обновления')->withInput();
    }

    public function destroy($id)
    {
        $result = $this->api->delete("hotels/{$id}");

        if (!empty($result['success'])) {
            return redirect()->route('hotels.index')->with('success', 'Дом удалён.');
        }
        return redirect()->back()->with('error', $result['message'] ?? 'Ошибка удаления');
    }
}
