<?php

namespace App\Policies;

use App\Models\CustomerTag;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerTagPolicy
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

    public function show(User $currentUser, CustomerTag $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, CustomerTag $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, CustomerTag $target)
    {
        return $currentUser->isAdmin();
    }
}