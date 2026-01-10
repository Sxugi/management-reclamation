<?php

namespace App\Policies;

use App\Models\AnggaranReklamasi;
use App\Models\Lahan;
use App\Models\User;

class AnggaranReklamasiPolicy
{
    /**
     * Perform pre-authorization checks.
     */
    public function before(User $user, string $ability): bool|null
    {
        // Admin can do anything
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any anggaran reklamasi for a lahan.
     */
    public function viewAny(User $user, Lahan $lahan): bool
    {
        // User can view if they have access to the lahan
        return $user->hasAccessToLahan($lahan);
    }

    /**
     * Determine whether the user can view the anggaran reklamasi.
     */
    public function view(User $user, AnggaranReklamasi $anggaran): bool
    {
        // User can view if they have access to the lahan
        return $user->hasAccessToLahan($anggaran->lahan);
    }

    /**
     * Determine whether the user can create anggaran reklamasi. 
     */
    public function create(User $user, Lahan $lahan): bool
    {
        // User can create if owner or editor of the lahan
        return $user->canEditLahan($lahan);
    }

    /**
     * Determine whether the user can update the anggaran reklamasi.
     */
    public function update(User $user, AnggaranReklamasi $anggaran): bool
    {
        // User can update if owner or editor of the lahan
        return $user->canEditLahan($anggaran->lahan);
    }

    /**
     * Determine whether the user can delete the anggaran reklamasi. 
     */
    public function delete(User $user, AnggaranReklamasi $anggaran): bool
    {
        // Only owner can delete anggaran
        return $user->isOwnerOfLahan($anggaran->lahan);
    }
}