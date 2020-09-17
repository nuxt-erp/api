<?php

namespace Modules\Inventory\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Inventory\Entities\ProductFamilyAttribute;

class ProductFamilyAttributePolicy
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

    public function show(User $currentUser, ProductFamilyAttribute $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, ProductFamilyAttribute $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, ProductFamilyAttribute $target)
    {
        return $currentUser->isAdmin();
    }
}