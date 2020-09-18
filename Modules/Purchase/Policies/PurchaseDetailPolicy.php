<?php

namespace Modules\Purchase\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Purchase\Entities\PurchaseDetail;

class PurchaseDetailPolicy
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

    public function show(User $currentUser, PurchaseDetail $target)
    {
        return $currentUser->isAdmin();
    }

    public function store(User $currentUser)
    {
        return $currentUser->isAdmin();
    }

    public function update(User $currentUser, PurchaseDetail $target)
    {
        return $currentUser->isAdmin();
    }

    public function destroy(User $currentUser, PurchaseDetail $target)
    {
        return $currentUser->isAdmin();
    }
}
