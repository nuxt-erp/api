<?php

namespace App\Policies;

use App\Models\Flow;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FlowPolicy
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
        return $currentUser->isAdmin();
    }

    public function show(User $currentUser, Flow $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, Flow $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, Flow $target)
    {
        return $currentUser->isAdmin();
    }
}
