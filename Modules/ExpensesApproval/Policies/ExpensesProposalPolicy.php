<?php

namespace Modules\ExpensesApproval\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\ExpensesApproval\Entities\ExpensesProposal;

class ExpensesProposalPolicy
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
        return $currentUser->hasRole('sponsor', 'team_leader', 'user', 'buyer');
    }

    public function index(User $currentUser)
    {
        return $currentUser->hasRole('sponsor', 'team_leader', 'user', 'buyer');
    }

    public function show(User $currentUser, ExpensesProposal $target)
    {
        return $currentUser->hasRole('sponsor', 'team_leader', 'user', 'buyer');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('sponsor', 'team_leader', 'user', 'buyer');
    }

    public function update(User $currentUser, ExpensesProposal $target)
    {
        return $currentUser->hasRole('sponsor', 'team_leader', 'user', 'buyer');
    }

    public function destroy(User $currentUser, ExpensesProposal $target)
    {
        return $currentUser->hasRole('sponsor', 'team_leader', 'user', 'buyer');
    }
}
