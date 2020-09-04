<?php

namespace Modules\ExpensesApproval\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\ExpensesApproval\Entities\ExpensesRule;

class ExpensesRulePolicy
{
    use HandlesAuthorization;

    public function list(User $currentUser)
    {
        return TRUE;
    }

    public function index(User $currentUser)
    {
        return TRUE;
    }

    public function show(User $currentUser, ExpensesRule $target)
    {
        return TRUE;
    }

    public function store(User $currentUser)
    {
        return TRUE;
    }

    public function update(User $currentUser, ExpensesRule $target)
    {
        return TRUE;
    }

    public function destroy(User $currentUser, ExpensesRule $target)
    {
        return TRUE;
    }
}