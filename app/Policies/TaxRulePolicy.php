<?php

namespace App\Policies;

use App\Models\TaxRule;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaxRulePolicy
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

    public function show(User $currentUser, TaxRule $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, TaxRule $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, TaxRule $target)
    {
        return $currentUser->isAdmin();
    }
}
