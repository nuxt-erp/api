<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function show(User $currentUser, User $target)
    {
        return $currentUser->isAdmin() || $target->id === $currentUser->id;
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, User $target)
    {
        return $currentUser->isAdmin() || $target->id === $currentUser->id;
    }

    public function destroy(User $currentUser, User $target)
    {
        return $currentUser->isAdmin();
    }
}
