<?php

namespace Modules\Inventory\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Inventory\Entities\Transfer;

class TransferPolicy
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

    public function show(User $currentUser, Transfer $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, Transfer $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, Transfer $target)
    {
        return $currentUser->isAdmin();
    }
}
