<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    protected $table = 'amenities';

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'category',
        'description',
        'sort_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    public function hotels(): BelongsToMany
    {
        return $this->belongsToMany(Hotel::class, 'hotel_amenities');
    }

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_amenities');
    }

    public function getIconClassAttribute(): string
    {
        return $this->icon ? 'fa fa-' . $this->icon : 'fa fa-tag';
    }

    public function getCategoryNameAttribute(): string
    {
        return match($this->category) {
            'hotel' => 'Отельные удобства',
            'rooms' => 'Удобства в номере',
            default => $this->category
        };
    }
}
