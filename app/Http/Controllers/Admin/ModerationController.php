<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    public function index()
    {
        $pendingHotels = Hotel::pending()
            ->with('owner')
            ->whereNotNull('owner_id')
            ->latest()
            ->get();
        
        $pendingRooms = Room::pending()
            ->with(['hotel.owner'])
            ->whereHas('hotel', function($query) {
                $query->whereNotNull('owner_id');
            })
            ->latest()
            ->get();
        
        return view('admin.moderation.index', compact('pendingHotels', 'pendingRooms'));
    }

    public function approveHotel(Hotel $hotel)
    {
        $hotel->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Дом одобрен!');
    }

    public function rejectHotel(Request $request, Hotel $hotel)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $hotel->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'is_active' => false,
        ]);

        return redirect()->back()->with('success', 'Дом отклонён.');
    }

    public function approveRoom(Room $room)
    {
        $room->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'is_active' => true,
            'is_available' => true,
        ]);

        return redirect()->back()->with('success', 'Квартира одобрена!');
    }

    public function rejectRoom(Request $request, Room $room)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $room->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'is_active' => false,
            'is_available' => false,
        ]);

        return redirect()->back()->with('success', 'Квартира отклонена.');
    }
}
