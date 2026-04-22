<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Hotel extends Model
{
    use SoftDeletes;

    protected $table = 'hotels';

    protected $fillable = [
        'owner_id', 'name', 'description', 'short_description', 'address', 'city',
        'country', 'postal_code', 'latitude', 'longitude', 'phone',
        'email', 'website', 'stars', 'rating', 'review_count',
        'rating_cleanliness', 'rating_comfort', 'rating_location',
        'rating_service', 'rating_value', 'main_image', 'gallery',
        'check_in_time', 'check_out_time', 'min_price', 'max_price',
        'is_active', 'is_featured', 'is_approved', 'allows_pets',
        'allows_children', 'allows_smoking', 'has_wheelchair_access',
        'languages', 'nearby_places', 'house_rules', 'status',
        'rejection_reason', 'approved_at', 'approved_by'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'rating' => 'decimal:2',
        'gallery' => 'json',
        'languages' => 'json',
        'nearby_places' => 'json',
        'house_rules' => 'json',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_approved' => 'boolean',
        'allows_pets' => 'boolean',
        'allows_children' => 'boolean',
        'allows_smoking' => 'boolean',
        'has_wheelchair_access' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

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

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'hotel_amenities');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Accessor для получения минимальной цены из номеров
    public function getMinPriceAttribute()
    {
        // Если есть сохраненная минимальная цена, используем её
        if (isset($this->attributes['min_price']) && $this->attributes['min_price'] > 0) {
            return $this->attributes['min_price'];
        }

        // Иначе вычисляем из номеров
        return $this->rooms()
            ->where('is_available', true)
            ->min('price_per_night') ?? 0;
    }

    // Accessor для получения максимальной цены из номеров
    public function getMaxPriceAttribute()
    {
        // Если есть сохраненная максимальная цена, используем её
        if (isset($this->attributes['max_price']) && $this->attributes['max_price'] > 0) {
            return $this->attributes['max_price'];
        }

        // Иначе вычисляем из номеров
        return $this->rooms()
            ->where('is_available', true)
            ->max('price_per_night') ?? 0;
    }
}
