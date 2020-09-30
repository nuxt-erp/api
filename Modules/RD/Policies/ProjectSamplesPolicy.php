<?php

namespace Modules\RD\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\RD\Entities\ProjectSamples;

class ProjectSamplesPolicy
{
    use HandlesAuthorization;

    public function list(User $currentUser)
    {
        return $currentUser->hasRole('rd_requester', 'admin');
    }

    public function index(User $currentUser)
    {
        return $currentUser->hasRole('rd_requester', 'admin');
    }

    public function show(User $currentUser, ProjectSamples $target)
    {
        return $currentUser->hasRole('rd_requester', 'admin');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('rd_requester', 'admin');
    }

    public function update(User $currentUser, ProjectSamples $target)
    {
        return $currentUser->hasRole('rd_requester', 'admin');
    }

    public function destroy(User $currentUser, ProjectSamples $target)
    {
        return $currentUser->hasRole('rd_requester', 'admin');
    }
}
