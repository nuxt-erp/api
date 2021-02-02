<?php

namespace App\Policies;

use App\Models\CronLog;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CronLogPolicy
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

    public function show(User $currentUser, CronLog $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, CronLog $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, CronLog $target)
    {
        return $currentUser->isAdmin();
    }
}
