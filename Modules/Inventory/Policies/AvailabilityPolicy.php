<?php

namespace Modules\Inventory\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Inventory\Entities\Availability;

class AvailabilityPolicy
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

    public function exportAll(User $currentUser)
    {
        return TRUE;
    }

    public function show(User $currentUser, Availability $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, Availability $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, Availability $target)
    {
        return $currentUser->isAdmin();
    }
}
