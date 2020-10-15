<?php

namespace Modules\ExpensesApproval\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\ExpensesApproval\Entities\Subcategory;

class SubcategoryPolicy
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
        return $currentUser->hasRole('sponsor', 'lead', 'user', 'buyer');
    }

    public function show(User $currentUser, Subcategory $target)
    {
        return $currentUser->hasRole('sponsor', 'lead');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('sponsor', 'lead');
    }

    public function update(User $currentUser, Subcategory $target)
    {
        return $currentUser->hasRole('sponsor', 'lead');
    }

    public function destroy(User $currentUser, Subcategory $target)
    {
        return $currentUser->hasRole('sponsor', 'lead');
    }
}
