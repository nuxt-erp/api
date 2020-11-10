<?php

namespace Modules\Inventory\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Inventory\Entities\PriceTierItems;

class PriceTierItemsPolicy
{
    use HandlesAuthorization;

    public function list(User $currentUser)
    {
        return TRUE;
    }

    public function index(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function show(User $currentUser, PriceTierItems $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, PriceTierItems $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, PriceTierItems $target)
    {
        return $currentUser->isAdmin();
    }
}
