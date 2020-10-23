<?php

namespace App\Policies;

use App\Models\TaxRuleComponent;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaxRuleComponentPolicy
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

    public function show(User $currentUser, TaxRuleComponent $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, TaxRuleComponent $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, TaxRuleComponent $target)
    {
        return $currentUser->isAdmin();
    }
}
