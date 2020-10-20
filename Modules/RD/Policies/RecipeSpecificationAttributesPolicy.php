<?php

namespace Modules\RD\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\RD\Entities\RecipeSpecificationAttributes;

class RecipeSpecificationAttributesPolicy
{
    use HandlesAuthorization;

    public function list(User $currentUser)
    {
        return TRUE;
    }

    public function index(User $currentUser)
    {
        return $currentUser->hasRole('rd_quality_control', 'admin');
    }

    public function show(User $currentUser, RecipeSpecificationAttributes $target)
    {
        return $currentUser->hasRole('rd_quality_control', 'admin');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('rd_quality_control', 'admin');
    }

    public function update(User $currentUser, RecipeSpecificationAttributes $target)
    {
        return $currentUser->hasRole('rd_quality_control', 'admin');
    }

    public function destroy(User $currentUser, RecipeSpecificationAttributes $target)
    {
        return $currentUser->hasRole('rd_quality_control', 'admin');
    }
}
