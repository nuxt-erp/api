<?php

namespace Modules\Sales\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Sales\Entities\DiscountTag;

class DiscountTagPolicy
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

    public function show(User $currentUser, DiscountTag $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, DiscountTag $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, DiscountTag $target)
    {
        return $currentUser->isAdmin();
    }
}
