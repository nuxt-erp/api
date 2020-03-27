<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function show(User $currentUser, Role $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, Role $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, Role $target)
    {
        return $currentUser->isAdmin();
    }
}
