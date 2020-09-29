<?php

namespace Modules\Inventory\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Inventory\Entities\ProductReorderLevel;

class ProductReorderLevelPolicy
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

    public function show(User $currentUser, ProductReorderLevel $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, ProductReorderLevel $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, ProductReorderLevel $target)
    {
        return $currentUser->isAdmin();
    }
}
