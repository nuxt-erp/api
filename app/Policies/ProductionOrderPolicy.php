<?php

namespace App\Policies;

use App\Models\ProductionOrder;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductionOrderPolicy
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
        return $currentUser->hasRole('production');
    }

    public function show(User $currentUser, ProductionOrder $target)
    {
        return $currentUser->hasRole('production');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('production');
    }

    public function update(User $currentUser, ProductionOrder $target)
    {
        return $currentUser->hasRole('production');
    }

    public function destroy(User $currentUser, ProductionOrder $target)
    {
        return FALSE;
    }
}
