<?php

namespace App\Policies;

use App\Models\Flavor;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FlavorPolicy
{
    use HandlesAuthorization;

    public function show(User $currentUser, Flavor $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, Flavor $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, Flavor $target)
    {
        return $currentUser->isAdmin();
    }
}
