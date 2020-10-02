<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function list(User $currentUser)
    {
        return TRUE;
    }

    public function index(User $currentUser)
    {
        return $currentUser->hasRole('rd_requester', 'rd_supervisor', 'admin');
    }

    public function show(User $currentUser, Customer $target)
    {
        return $currentUser->hasRole('rd_requester', 'rd_supervisor', 'admin');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('rd_requester', 'rd_supervisor', 'admin');
    }

    public function update(User $currentUser, Customer $target)
    {
        return $currentUser->hasRole('rd_requester', 'rd_supervisor', 'admin');
    }

    public function destroy(User $currentUser, Customer $target)
    {
        return $currentUser->hasRole('rd_requester', 'rd_supervisor', 'admin');
    }
}
