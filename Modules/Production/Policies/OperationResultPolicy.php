<?php

namespace Modules\Production\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Production\Entities\OperationResult;

class OperationResultPolicy
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

    public function show(User $currentUser, OperationResult $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, OperationResult $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, OperationResult $target)
    {
        return $currentUser->isAdmin();
    }
}
