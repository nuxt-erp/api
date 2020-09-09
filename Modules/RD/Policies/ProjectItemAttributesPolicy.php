<?php

namespace Modules\RD\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\RD\Entities\ProjectItemAttributes;

class ProjectItemAttributesPolicy
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

    public function show(User $currentUser, ProjectItemAttributes $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, ProjectItemAttributes $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, ProjectItemAttributes $target)
    {
        return $currentUser->isAdmin();
    }
}
