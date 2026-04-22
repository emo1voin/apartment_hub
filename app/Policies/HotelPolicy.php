<?php

namespace App\Policies;

use App\Models\Hotel;
use App\Models\User;

class HotelPolicy
{
    public function update(User $user, Hotel $hotel): bool
    {
        return $user->id === $hotel->owner_id || $user->isAdmin();
    }

    public function delete(User $user, Hotel $hotel): bool
    {
        return $user->id === $hotel->owner_id || $user->isAdmin();
    }
}
