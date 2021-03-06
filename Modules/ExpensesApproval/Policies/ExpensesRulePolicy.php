<?php

namespace Modules\ExpensesApproval\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\ExpensesApproval\Entities\ExpensesRule;

class ExpensesRulePolicy
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
        return $currentUser->hasRole('sponsor', 'team_leader');
    }

    public function index(User $currentUser)
    {
        return $currentUser->hasRole('sponsor', 'team_leader');
    }

    public function show(User $currentUser, ExpensesRule $target)
    {
        return $currentUser->hasRole('sponsor', 'team_leader');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('sponsor', 'team_leader');
    }

    public function update(User $currentUser, ExpensesRule $target)
    {
        return $currentUser->hasRole('sponsor', 'team_leader');
    }

    public function destroy(User $currentUser, ExpensesRule $target)
    {
        return $currentUser->hasRole('sponsor', 'team_leader');
    }
}
