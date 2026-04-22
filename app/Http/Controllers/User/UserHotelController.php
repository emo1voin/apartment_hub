<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use Illuminate\Http\Request;

class UserHotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::where('owner_id', auth()->id())
            ->latest()
            ->paginate(10);
        
        return view('user.hotels.index', compact('hotels'));
    }

    public function create()
    {
        return view('user.hotels.create');
    }

    public function store(Request $request)
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
        ]);

        $hotel = Hotel::create([
            ...$validated,
            'owner_id' => auth()->id(),
            'status' => 'pending',
            'is_active' => false,
        ]);

        return redirect()->route('user.hotels.index')
            ->with('success', 'Дом отправлен на модерацию!');
    }

    public function edit(Hotel $hotel)
    {
        $this->authorize('update', $hotel);
        
        return view('user.hotels.edit', compact('hotel'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $this->authorize('update', $hotel);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        $hotel->update([
            ...$validated,
            'status' => 'pending', // Отправляем на повторную модерацию
        ]);

        return redirect()->route('user.hotels.index')
            ->with('success', 'Дом обновлён и отправлен на модерацию!');
    }

    public function destroy(Hotel $hotel)
    {
        $this->authorize('delete', $hotel);
        
        $hotel->delete();

        return redirect()->route('user.hotels.index')
            ->with('success', 'Дом удалён.');
    }
}
