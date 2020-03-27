<?php

namespace App\Policies;

use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehousePolicy
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

    public function show(User $currentUser, Warehouse $target)
    {
        return $currentUser->hasRole('inventory');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('inventory');
    }

    public function update(User $currentUser, Warehouse $target)
    {
        return $currentUser->hasRole('inventory');
    }

    public function destroy(User $currentUser, Warehouse $target)
    {
        return FALSE;
    }
}
