<?php

namespace App\Policies;

use App\Models\ProductGroup;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductGroupPolicy
{
    use HandlesAuthorization;

    public function show(User $currentUser, ProductGroup $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, ProductGroup $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, ProductGroup $target)
    {
        return $currentUser->isAdmin();
    }
}
