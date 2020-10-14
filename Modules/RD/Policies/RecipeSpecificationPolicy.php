<?php

namespace Modules\RD\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecipeSpecificationPolicy
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

    public function show(User $currentUser, EntityName $target)
    {
        return $currentUser->hasRole('rd_quality_control', 'admin');
    }

    public function store(User $currentUser)
    {
        return $currentUser->hasRole('rd_quality_control', 'admin');
    }

    public function update(User $currentUser, EntityName $target)
    {
        return $currentUser->hasRole('rd_quality_control', 'admin');
    }

    public function destroy(User $currentUser, EntityName $target)
    {
        return $currentUser->hasRole('rd_quality_control', 'admin');
    }
}
