<?php

namespace Modules\Purchase\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Purchase\Entities\PurchaseTrackingNumber;

class PurchaseTrackingNumberPolicy
{
    use HandlesAuthorization;

    public function list(User $currentUser)
    {
        return TRUE;
    }

    public function index(User $currentUser)
    {
        return TRUE;
    }

    public function show(User $currentUser, PurchaseTrackingNumber $target)
    {
        return TRUE;
    }

    public function store(User $currentUser)
    {
        return TRUE;
    }

    public function update(User $currentUser, PurchaseTrackingNumber $target)
    {
        return TRUE;
    }

    public function destroy(User $currentUser, PurchaseTrackingNumber $target)
    {
        return TRUE;
    }
}
