<?php

namespace Modules\RD\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\RD\Entities\ProjectSampleLogs;

class ProjectSampleLogsPolicy
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

    public function show(User $currentUser, ProjectSampleLogs $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, ProjectSampleLogs $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, ProjectSampleLogs $target)
    {
        return $currentUser->isAdmin();
    }
}
