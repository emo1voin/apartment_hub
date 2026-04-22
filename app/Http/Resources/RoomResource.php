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
            'description' => $this->description,
            'type' => $this->type,
            'price_per_night' => $this->price_per_night,
            'capacity_adults' => $this->capacity_adults,
            'capacity_children' => $this->capacity_children,
            'bed_type' => $this->bed_type,
            'bed_count' => $this->bed_count,
            'size_sqm' => $this->size_sqm,
            'is_available' => $this->is_available,
            'available_quantity' => $this->available_quantity,
            'amenities' => $this->whenLoaded('amenities', function() {
                return $this->amenities->pluck('name');
            }),
            'hotel' => new HotelResource($this->whenLoaded('hotel')),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
