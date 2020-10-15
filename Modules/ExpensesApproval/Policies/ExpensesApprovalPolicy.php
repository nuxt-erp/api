<?php

namespace Modules\ExpensesApproval\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\ExpensesApproval\Entities\ExpensesApproval;

class ExpensesApprovalPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function list(User $currentUser)
    {
        return $currentUser->hasRole('sponsor', 'lead');
    }

    public function index(User $currentUser)
    {
        return $currentUser->hasRole('sponsor', 'lead');
    }

    public function show(User $currentUser, ExpensesApproval $target)
    {
        return $currentUser->hasRole('sponsor', 'lead');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('sponsor', 'lead');
    }

    public function update(User $currentUser, ExpensesApproval $target)
    {
        return $currentUser->hasRole('sponsor', 'lead');
    }

    public function destroy(User $currentUser, ExpensesApproval $target)
    {
        return $currentUser->hasRole('sponsor', 'lead');
    }
}
