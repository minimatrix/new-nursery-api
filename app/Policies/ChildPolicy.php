<?php

namespace App\Policies;

use App\Models\Child;
use App\Models\User;

class ChildPolicy
{
    public function view(User $user, Child $child): bool
    {
        if ($user->type === 'staff') {
            return $user->nursery_id === $child->nursery_id;
        }

        return $this->isParentOfChild($user, $child);
    }

    public function update(User $user, Child $child): bool
    {
        if ($user->type === 'staff') {
            return $user->nursery_id === $child->nursery_id;
        }

        return $this->isParentOfChild($user, $child);
    }

    private function isParentOfChild(User $user, Child $child): bool
    {
        return $child->guardians()
            ->where('user_id', $user->id)
            ->exists();
    }
}
