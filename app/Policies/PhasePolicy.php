<?php

namespace App\Policies;

use App\Models\Phase;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PhasePolicy
{
    use HandlesAuthorization;

    public function before($currentUser, $ability)
    {
        if ($currentUser->isAdmin()) {
            return true;
        }
    }

    public function index(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function show(User $currentUser, Phase $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, Phase $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, Phase $target)
    {
        return $currentUser->isAdmin();
    }
}
