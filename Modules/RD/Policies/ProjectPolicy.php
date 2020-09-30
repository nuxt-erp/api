<?php

namespace Modules\RD\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\RD\Entities\Project;

class ProjectPolicy
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

    public function show(User $currentUser, Project $target)
    {
        return $currentUser->hasRole('rd_requester', 'admin');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('rd_requester', 'admin');
    }

    public function update(User $currentUser, Project $target)
    {
        return $currentUser->hasRole('rd_requester', 'admin');
    }

    public function destroy(User $currentUser, Project $target)
    {
        return $currentUser->hasRole('admin');
    }
}
