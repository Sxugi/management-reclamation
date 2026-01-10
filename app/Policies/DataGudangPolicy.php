<?php

namespace App\Policies;

use App\Models\DataGudang;
use App\Models\Lahan;
use App\Models\User;

class DataGudangPolicy
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
     * Determine whether the user can view any gudang for a lahan.
     */
    public function viewAny(User $user, Lahan $lahan): bool
    {
        return $user->hasAccessToLahan($lahan);
    }

    /**
     * Determine whether the user can view the gudang.
     */
    public function view(User $user, DataGudang $gudang): bool
    {
        return $user->hasAccessToLahan($gudang->lahan);
    }

    /**
     * Determine whether the user can create gudang.
     */
    public function create(User $user, Lahan $lahan): bool
    {
        return $user->canEditLahan($lahan);
    }

    /**
     * Determine whether the user can update the gudang.
     */
    public function update(User $user, DataGudang $gudang): bool
    {
        return $user->canEditLahan($gudang->lahan);
    }

    /**
     * Determine whether the user can delete the gudang.
     */
    public function delete(User $user, DataGudang $gudang): bool
    {
        // Only owner can delete data
        return $user->isOwnerOfLahan($gudang->lahan);
    }
}