<?php

namespace Modules\Sales\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DiscountRulePolicy
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

    public function show(User $currentUser, EntityName $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, EntityName $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, EntityName $target)
    {
        return $currentUser->isAdmin();
    }
}
