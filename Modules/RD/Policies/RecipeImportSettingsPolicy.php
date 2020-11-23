<?php

namespace Modules\RD\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\RD\Entities\RecipeImportSettings;

class RecipeImportSettingsPolicy
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

    public function show(User $currentUser, RecipeImportSettings $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, RecipeImportSettings $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, RecipeImportSettings $target)
    {
        return $currentUser->isAdmin();
    }
}
