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

    public function index(User $currentUser)
    {
        return $currentUser->hasRole('rd_requester', 'admin');
    }

    public function show(User $currentUser, Parameter $target)
    {
        return $currentUser->hasRole('rd_requester', 'admin');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('rd_requester', 'admin');
    }

    public function update(User $currentUser, Parameter $target)
    {
        return $currentUser->hasRole('rd_requester', 'admin');
    }

    public function destroy(User $currentUser, Parameter $target)
    {
        return $currentUser->hasRole('rd_requester', 'admin');
    }
}
