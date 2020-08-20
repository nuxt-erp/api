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
        return $currentUser->hasRole('supervisor');
    }

    public function show(User $currentUser, ProductionOrder $target)
    {
        return $currentUser->hasRole('supervisor');
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, ProductionOrder $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, ProductionOrder $target)
    {
        return $currentUser->isAdmin();
    }
}
