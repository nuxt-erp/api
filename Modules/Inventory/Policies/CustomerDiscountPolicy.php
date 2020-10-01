<?php

namespace Modules\Inventory\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Inventory\Entities\CustomerDiscount;

class CustomerDiscountPolicy
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

    public function show(User $currentUser, CustomerDiscount $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, CustomerDiscount $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, CustomerDiscount $target)
    {
        return $currentUser->isAdmin();
    }
}
