<?php

namespace Modules\Sales\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Sales\Entities\DiscountRule;

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

    public function show(User $currentUser, DiscountRule $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, DiscountRule $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, DiscountRule $target)
    {
        return $currentUser->isAdmin();
    }
}
