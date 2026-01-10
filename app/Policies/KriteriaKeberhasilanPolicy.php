<?php

namespace App\Policies;

use App\Models\KriteriaKeberhasilan;
use App\Models\Lahan;
use App\Models\User;

class KriteriaKeberhasilanPolicy
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
     * Determine whether the user can view the criteria page.
     */
    public function viewAny(User $user, Lahan $lahan): bool
    {
        return $user->hasAccessToLahan($lahan);
    }

    /**
     * Determine whether the user can view specific criteria.
     */
    public function view(User $user, KriteriaKeberhasilan $kriteria): bool
    {
        return $user->hasAccessToLahan($kriteria->lahan);
    }

    /**
     * Determine whether the user can create/edit criteria for a lahan.
     * Note: Since Kriteria is 1:1 with Lahan and uses firstOrCreate, 
     * 'create' permission covers the edit/update action on the Lahan level.
     */
    public function create(User $user, Lahan $lahan): bool
    {
        return $user->canEditLahan($lahan);
    }

    /**
     * Determine whether the user can update the specific criteria instance.
     */
    public function update(User $user, KriteriaKeberhasilan $kriteria): bool
    {
        return $user->canEditLahan($kriteria->lahan);
    }

    /**
     * Determine whether the user can generate PDF.
     */
    public function generatePDF(User $user, Lahan $lahan): bool
    {
        return $user->hasAccessToLahan($lahan);
    }
}