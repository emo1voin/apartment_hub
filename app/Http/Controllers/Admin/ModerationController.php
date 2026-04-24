<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use App\Services\ApiService;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    public function __construct(private ApiService $api) {}

    public function index()
    {
        $pendingHotels = Hotel::pending()->with('owner')->whereNotNull('owner_id')->latest()->paginate(10, ['*'], 'hotels_page');
        $pendingRooms = Room::pending()->with(['hotel.owner'])
            ->whereHas('hotel', fn($q) => $q->whereNotNull('owner_id'))->latest()->paginate(10, ['*'], 'rooms_page');

        return view('admin.moderation.index', compact('pendingHotels', 'pendingRooms'));
    }

    public function approveHotel($id)
    {
        $result = $this->api->post("moderation/hotels/{$id}/approve");
        return redirect()->back()->with('success', $result['message'] ?? 'Дом одобрен!');
    }

    public function rejectHotel(Request $request, $id)
    {
        $result = $this->api->post("moderation/hotels/{$id}/reject", [
            'rejection_reason' => $request->rejection_reason,
        ]);
        return redirect()->back()->with('success', $result['message'] ?? 'Дом отклонён.');
    }

    public function approveRoom($id)
    {
        $result = $this->api->post("moderation/rooms/{$id}/approve");
        return redirect()->back()->with('success', $result['message'] ?? 'Квартира одобрена!');
    }

    public function rejectRoom(Request $request, $id)
    {
        $result = $this->api->post("moderation/rooms/{$id}/reject", [
            'rejection_reason' => $request->rejection_reason,
        ]);
        return redirect()->back()->with('success', $result['message'] ?? 'Квартира отклонена.');
    }
}
