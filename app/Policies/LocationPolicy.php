<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationPolicy
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

    public function show(User $currentUser, Location $target)
    {
        return $currentUser->hasRole('inventory');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('inventory');
    }

    public function update(User $currentUser, Location $target)
    {
        return $currentUser->hasRole('inventory');
    }

    public function destroy(User $currentUser, Location $target)
    {
        return FALSE;
    }
}
