<?php

namespace App\Policies;

use App\Models\Action;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActionPolicy
{
    use HandlesAuthorization;

    public function show(User $currentUser, Action $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return FALSE;
    }

    public function update(User $currentUser, Action $target)
    {
        return FALSE;
    }

    public function destroy(User $currentUser, Action $target)
    {
        return FALSE;
    }
}
