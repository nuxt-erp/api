<?php

namespace App\Policies;

use App\Models\ProductAvailability;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductAvailabilityPolicy
{
    use HandlesAuthorization;

    public function before($currentUser, $ability)
    {
        if ($currentUser->isAdmin()) {
            return true;
        }
    }

    public function index(User $currentUser)
    {
        return $currentUser->hasRole('inventory');
    }

    public function show(User $currentUser, ProductAvailability $target)
    {
        return $currentUser->hasRole('inventory');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('inventory');
    }

    public function update(User $currentUser, ProductAvailability $target)
    {
        return $currentUser->hasRole('inventory');
    }

    public function destroy(User $currentUser, ProductAvailability $target)
    {
        return FALSE;
    }
}
