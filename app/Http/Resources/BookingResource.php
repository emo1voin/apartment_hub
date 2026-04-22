<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'booking_number' => $this->booking_number,
            'user_id' => $this->user_id,
            'hotel_id' => $this->hotel_id,
            'room_id' => $this->room_id,
            'check_in' => $this->check_in,
            'check_out' => $this->check_out,
            'nights' => $this->nights,
            'adults' => $this->adults,
            'children' => $this->children,
            'total_guests' => $this->total_guests,
            'price_per_night' => $this->price_per_night,
            'subtotal' => $this->subtotal,
            'tax_amount' => $this->tax_amount,
            'service_fee' => $this->service_fee,
            'total_price' => $this->total_price,
            'special_requests' => $this->special_requests,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'cancelled_at' => $this->cancelled_at,
            'cancellation_reason' => $this->cancellation_reason,
            'user' => new UserResource($this->whenLoaded('user')),
            'hotel' => new HotelResource($this->whenLoaded('hotel')),
            'room' => new RoomResource($this->whenLoaded('room')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
