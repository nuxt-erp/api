<?php

namespace Modules\Purchase\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Purchase\Entities\Purchase;

class PurchasePolicy
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

    public function show(User $currentUser, Purchase $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, Purchase $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, Purchase $target)
    {
        return $currentUser->isAdmin();
    }
}
