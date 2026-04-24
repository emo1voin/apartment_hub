<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Hotel;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request, $hotelId)
    {
        $hotel = Hotel::findOrFail($hotelId);
        $rooms = $hotel->rooms()->approved()->where('is_active', true)->paginate(12);

        return view('rooms.index', compact('rooms', 'hotel'));
    }

    public function show($id)
    {
        $room = Room::with(['hotel', 'amenities'])->findOrFail($id);
        return view('rooms.show', compact('room'));
    }
}
