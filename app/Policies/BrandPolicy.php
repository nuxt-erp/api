<?php

namespace App\Policies;

use App\Models\Brand;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BrandPolicy
{
    use HandlesAuthorization;

    public function show(User $currentUser, Brand $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, Brand $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, Brand $target)
    {
        return $currentUser->isAdmin();
    }
}
