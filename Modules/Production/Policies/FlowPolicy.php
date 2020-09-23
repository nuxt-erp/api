<?php

namespace Modules\Production\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Production\Entities\Flow;

class FlowPolicy
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
