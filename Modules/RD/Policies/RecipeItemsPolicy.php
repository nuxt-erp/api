<?php

namespace Modules\RD\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\RD\Entities\RecipeItems;

class RecipeItemsPolicy
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

    public function show(User $currentUser, RecipeItems $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, RecipeItems $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, RecipeItems $target)
    {
        return $currentUser->isAdmin();
    }
}
