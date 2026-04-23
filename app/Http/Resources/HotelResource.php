<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HotelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'owner_id' => $this->owner_id,
            'name' => $this->name,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'postal_code' => $this->postal_code,
            'phone' => $this->phone,
            'email' => $this->email,
            'website' => $this->website,
            'stars' => $this->stars,
            'rating' => $this->rating,
            'review_count' => $this->review_count,
            'main_image' => $this->main_image,
            'gallery' => $this->gallery,
            'check_in_time' => $this->check_in_time,
            'check_out_time' => $this->check_out_time,
            'min_price' => $this->min_price,
            'max_price' => $this->max_price,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'status' => $this->status,
            'rejection_reason' => $this->rejection_reason,
            'approved_at' => $this->approved_at?->toISOString(),
            'amenities' => $this->whenLoaded('amenities', function () {
                return $this->amenities->pluck('name');
            }),
            'rooms' => RoomResource::collection($this->whenLoaded('rooms')),
            'owner' => $this->whenLoaded('owner', function () {
                return ['id' => $this->owner->id, 'name' => $this->owner->name];
            }),
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
