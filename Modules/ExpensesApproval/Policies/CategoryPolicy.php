<?php

namespace Modules\ExpensesApproval\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\ExpensesApproval\Entities\Category;

class CategoryPolicy
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
        return $currentUser->hasRole('sponsor', 'team_leader', 'user', 'buyer');
    }

    public function show(User $currentUser, Category $target)
    {
        return $currentUser->hasRole('sponsor', 'team_leader');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('sponsor', 'team_leader');
    }

    public function update(User $currentUser, Category $target)
    {
        return $currentUser->hasRole('sponsor', 'team_leader');
    }

    public function destroy(User $currentUser, Category $target)
    {
        return $currentUser->hasRole('sponsor', 'team_leader');
    }
}
