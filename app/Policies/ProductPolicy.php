<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function show(User $currentUser, Product $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, Product $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, Product $target)
    {
        return $currentUser->isAdmin();
    }
}
