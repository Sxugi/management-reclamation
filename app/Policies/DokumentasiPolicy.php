<?php

namespace App\Policies;

use App\Models\Dokumentasi;
use App\Models\Lahan;
use App\Models\User;

class DokumentasiPolicy
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
     * Determine whether the user can view the documentation list.
     */
    public function viewAny(User $user, Lahan $lahan): bool
    {
        return $user->hasAccessToLahan($lahan);
    }

    /**
     * Determine whether the user can create documentation.
     */
    public function create(User $user, Lahan $lahan): bool
    {
        return $user->canEditLahan($lahan);
    }

    /**
     * Determine whether the user can update the documentation.
     */
    public function update(User $user, Dokumentasi $dokumentasi): bool
    {
        return $user->canEditLahan($dokumentasi->lahan);
    }

    /**
     * Determine whether the user can delete the documentation.
     */
    public function delete(User $user, Dokumentasi $dokumentasi): bool
    {
        return $user->isOwnerOfLahan($dokumentasi->lahan);
    }
}