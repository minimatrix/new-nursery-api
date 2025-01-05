<?php

namespace App\Policies;

use App\Models\PricingBand;
use App\Models\User;

class PricingBandPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->type === 'staff';
    }

    public function view(User $user, PricingBand $pricingBand): bool
    {
        return $user->type === 'staff'
            && $user->nursery_id === $pricingBand->nursery_id;
    }

    public function create(User $user): bool
    {
        return $user->type === 'staff' && $user->is_admin;
    }

    public function update(User $user, PricingBand $pricingBand): bool
    {
        return $user->type === 'staff'
            && $user->nursery_id === $pricingBand->nursery_id
            && $user->is_admin;
    }

    public function delete(User $user, PricingBand $pricingBand): bool
    {
        return $user->type === 'staff'
            && $user->nursery_id === $pricingBand->nursery_id
            && $user->is_admin;
    }
}
