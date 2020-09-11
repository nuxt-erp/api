<?php

namespace Modules\ExpensesApproval\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\ExpensesApproval\Entities\ExpensesAttachment;

class ExpensesAttachmentPolicy
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
        return TRUE;
    }

    public function index(User $currentUser)
    {
        return $currentUser->hasRole('director', 'team_leader', 'user', 'buyer');
    }

    public function show(User $currentUser, ExpensesAttachment $target)
    {
        return $currentUser->hasRole('director', 'team_leader', 'user', 'buyer');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('director', 'team_leader', 'user', 'buyer');
    }

    public function update(User $currentUser, ExpensesAttachment $target)
    {
        return $currentUser->hasRole('director', 'team_leader', 'user', 'buyer');
    }

    public function destroy(User $currentUser, ExpensesAttachment $target)
    {
        return $currentUser->hasRole('director', 'team_leader', 'user', 'buyer');
    }
}
