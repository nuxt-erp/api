<?php

namespace Modules\RD\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\RD\Entities\RecipeSpecification;

class RecipeSpecificationPolicy
{
    use HandlesAuthorization;

    public function list(User $currentUser)
    {
        return TRUE;
    }

    public function index(User $currentUser)
    {
        return $currentUser->hasRole('rd_quality_control', 'admin', 'rd_flavorist');
    }

    public function show(User $currentUser, RecipeSpecification $target)
    {
        return $currentUser->hasRole('rd_quality_control', 'admin', 'rd_flavorist');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('rd_quality_control', 'admin');
    }

    public function update(User $currentUser, RecipeSpecification $target)
    {
        return $currentUser->hasRole('rd_quality_control', 'admin');
    }

    public function destroy(User $currentUser, RecipeSpecification $target)
    {
        return $currentUser->hasRole('rd_quality_control', 'admin');
    }
}
