<?php

namespace Modules\Production\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Production\Entities\Phase;

class PhasePolicy
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

    public function show(User $currentUser, Phase $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, Phase $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, Phase $target)
    {
        return $currentUser->isAdmin();
    }
}
