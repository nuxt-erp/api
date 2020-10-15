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
        return $currentUser->hasRole('rd_flavorist', 'rd_requester', 'rd_supervisor', 'admin');
    }

    public function show(User $currentUser, Parameter $target)
    {
        return $currentUser->hasRole('rd_requester', 'rd_supervisor', 'admin');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('rd_requester', 'rd_supervisor', 'rd_quality_control', 'admin');
    }

    public function update(User $currentUser, Parameter $target)
    {
        return $currentUser->hasRole('admin');
    }

    public function destroy(User $currentUser, Parameter $target)
    {
        return $currentUser->hasRole('admin');
    }
}
