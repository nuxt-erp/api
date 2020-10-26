<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;

    public function list(User $currentUser)
    {
        return TRUE;
    }

    public function index(User $currentUser)
    {
        return $currentUser->hasRole('admin');
    }

    public function show(User $currentUser, Contact $target)
    {
        return $currentUser->hasRole('admin');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('admin');
    }

    public function update(User $currentUser, Contact $target)
    {
        return $currentUser->hasRole('admin');
    }

    public function destroy(User $currentUser, Contact $target)
    {
        return $currentUser->hasRole('admin');
    }
}
