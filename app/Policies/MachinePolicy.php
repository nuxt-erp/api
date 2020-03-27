<?php

namespace App\Policies;

use App\Models\Machine;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MachinePolicy
{
    use HandlesAuthorization;

    public function show(User $currentUser, Machine $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, Machine $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, Machine $target)
    {
        return $currentUser->isAdmin();
    }
}
