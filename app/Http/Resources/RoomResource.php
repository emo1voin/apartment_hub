<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hotel_id' => $this->hotel_id,
            'name' => $this->name,
            'room_number' => $this->room_number,
            'description' => $this->description,
            'type' => $this->type,
            'price_per_night' => $this->price_per_night,
            'weekend_price' => $this->weekend_price,
            'sale_price' => $this->sale_price,
            'capacity_adults' => $this->capacity_adults,
            'capacity_children' => $this->capacity_children,
            'total_capacity' => $this->total_capacity,
            'bed_type' => $this->bed_type,
            'bed_count' => $this->bed_count,
            'size_sqm' => $this->size_sqm,
            'main_image' => $this->main_image,
            'gallery' => $this->gallery,
            'is_active' => $this->is_active,
            'is_available' => $this->is_available,
            'quantity' => $this->quantity,
            'available_quantity' => $this->available_quantity,
            'status' => $this->status,
            'rejection_reason' => $this->rejection_reason,
            'amenities' => $this->whenLoaded('amenities', function () {
                return $this->amenities->pluck('name');
            }),
            'hotel' => new HotelResource($this->whenLoaded('hotel')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
