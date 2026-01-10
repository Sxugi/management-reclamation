<?php

namespace App\Policies;

use App\Models\Pohon;
use App\Models\Lahan;
use App\Models\User;

class PohonPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any pohon for a lahan.
     */
    public function viewAny(User $user, Lahan $lahan): bool
    {
        return $user->hasAccessToLahan($lahan);
    }

    /**
     * Determine whether the user can view the pohon.
     */
    public function view(User $user, Pohon $pohon): bool
    {
        return $user->hasAccessToLahan($pohon->lahan);
    }

    /**
     * Determine whether the user can create pohon.
     */
    public function create(User $user, Lahan $lahan): bool
    {
        return $user->canEditLahan($lahan);
    }

    /**
     * Determine whether the user can update the pohon.
     */
    public function update(User $user, Pohon $pohon): bool
    {
        return $user->canEditLahan($pohon->lahan);
    }

    /**
     * Determine whether the user can delete the pohon.
     */
    public function delete(User $user, Pohon $pohon): bool
    {
        // Only owner can delete data pohon
        return $user->isOwnerOfLahan($pohon->lahan);
    }
}