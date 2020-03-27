<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    public function show(User $currentUser, Employee $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, Employee $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, Employee $target)
    {
        return $currentUser->isAdmin();
    }
}
