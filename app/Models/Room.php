<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use SoftDeletes;

    protected $table = 'rooms';

    protected $fillable = [
        'hotel_id', 'name', 'room_number', 'description', 'type',
        'capacity_adults', 'capacity_children', 'total_capacity',
        'size_sqm', 'bed_type', 'bed_count', 'price_per_night',
        'weekend_price', 'sale_price', 'price_is_per_person',
        'quantity', 'available_quantity', 'main_image', 'gallery',
        'is_active', 'is_available', 'status', 'rejection_reason',
        'approved_at', 'approved_by'
    ];

    protected $casts = [
        'gallery' => 'array',
        'price_is_per_person' => 'boolean',
        'is_active' => 'boolean',
        'is_available' => 'boolean',
        'capacity_adults' => 'integer',
        'capacity_children' => 'integer',
        'total_capacity' => 'integer',
        'bed_count' => 'integer',
        'quantity' => 'integer',
        'available_quantity' => 'integer',
        'price_per_night' => 'decimal:2',
        'weekend_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'room_amenities');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function isAvailable($checkIn, $checkOut, $excludeBookingId = null): bool
    {
        $query = $this->bookings()
            ->where('status', 'confirmed')
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<=', $checkIn)
                            ->where('check_out', '>=', $checkOut);
                    });
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return !$query->exists() && $this->is_available && $this->quantity > 0;
    }

    public function calculatePrice($checkIn, $checkOut): float
    {
        $days = (strtotime($checkOut) - strtotime($checkIn)) / 86400;
        return $this->price_per_night * $days;
    }
}
