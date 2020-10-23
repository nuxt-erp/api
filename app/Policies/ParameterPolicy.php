<?php

namespace App\Policies;

use App\Models\Parameter;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ParameterPolicy
{
    use HandlesAuthorization;

    public function list(User $currentUser)
    {
        return TRUE;
    }
    // parameters for everyone
    public function index(User $currentUser)
    {
        return TRUE;
    }

    // everyone should have the privileges to get more details about a parameter
    public function show(User $currentUser, Parameter $target)
    {
        return TRUE;
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, Parameter $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, Parameter $target)
    {
        return $currentUser->isAdmin();
    }
}
