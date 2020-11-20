<?php

namespace App\Policies;

use App\Models\SettingsImages;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingsImagesPolicy
{
    use HandlesAuthorization;

    public function list(User $currentUser)
    {
        return TRUE;
    }

    public function index(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function show(User $currentUser, SettingsImages $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, SettingsImages $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, SettingsImages $target)
    {
        return $currentUser->isAdmin();
    }
}
