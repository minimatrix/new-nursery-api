<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;

class RoomPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->type === 'staff';
    }

    public function view(User $user, Room $room): bool
    {
        if ($user->is_admin) {
            return $user->nursery_id === $room->nursery_id;
        }

        return $user->rooms()->where('rooms.id', $room->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->type === 'staff' && $user->is_admin;
    }

    public function update(User $user, Room $room): bool
    {
        return $user->type === 'staff'
            && $user->nursery_id === $room->nursery_id
            && $user->is_admin;
    }

    public function delete(User $user, Room $room): bool
    {
        return $user->type === 'staff'
            && $user->nursery_id === $room->nursery_id
            && $user->is_admin;
    }
}
