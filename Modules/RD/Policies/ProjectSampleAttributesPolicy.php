<?php

namespace Modules\RD\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\RD\Entities\ProjectSampleAttributes;

class ProjectSampleAttributesPolicy
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

    public function show(User $currentUser, ProjectSampleAttributes $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, ProjectSampleAttributes $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, ProjectSampleAttributes $target)
    {
        return $currentUser->isAdmin();
    }
}
