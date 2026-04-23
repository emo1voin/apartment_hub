<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HotelResource;
use App\Http\Resources\RoomResource;
use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ModerationController extends Controller
{
    public function index(): JsonResponse
    {
        $pendingHotels = Hotel::pending()->with('owner')->whereNotNull('owner_id')->latest()->get();
        $pendingRooms = Room::pending()->with(['hotel.owner'])
            ->whereHas('hotel', fn($q) => $q->whereNotNull('owner_id'))->latest()->get();

        return response()->json([
            'success' => true,
            'data' => [
                'hotels' => HotelResource::collection($pendingHotels),
                'rooms' => RoomResource::collection($pendingRooms),
            ],
        ]);
    }

    public function approveHotel(Request $request, Hotel $hotel): JsonResponse
    {
        $hotel->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $request->user()->id,
            'is_active' => true,
        ]);
        return response()->json(['success' => true, 'message' => 'Дом одобрен!']);
    }

    public function rejectHotel(Request $request, Hotel $hotel): JsonResponse
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);
        $hotel->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'is_active' => false,
        ]);
        return response()->json(['success' => true, 'message' => 'Дом отклонён']);
    }

    public function approveRoom(Request $request, Room $room): JsonResponse
    {
        $room->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $request->user()->id,
            'is_active' => true,
            'is_available' => true,
        ]);
        return response()->json(['success' => true, 'message' => 'Квартира одобрена!']);
    }

    public function rejectRoom(Request $request, Room $room): JsonResponse
    {
        $request->validate(['rejection_reason' => 'required|string|max:500']);
        $room->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'is_active' => false,
            'is_available' => false,
        ]);
        return response()->json(['success' => true, 'message' => 'Квартира отклонена']);
    }
}
