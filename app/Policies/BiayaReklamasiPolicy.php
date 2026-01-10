<?php

namespace App\Policies;

use App\Models\BiayaReklamasi;
use App\Models\Lahan;
use App\Models\User;

class BiayaReklamasiPolicy
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
     * Determine whether the user can view the cost plan list.
     */
    public function viewAny(User $user, Lahan $lahan): bool
    {
        return $user->hasAccessToLahan($lahan);
    }

    /**
     * Determine whether the user can create a cost plan.
     */
    public function create(User $user, Lahan $lahan): bool
    {
        return $user->canEditLahan($lahan);
    }

    /**
     * Determine whether the user can update the cost plan.
     */
    public function update(User $user, BiayaReklamasi $rencanaBiaya): bool
    {
        return $user->canEditLahan($rencanaBiaya->lahan);
    }

    /**
     * Determine whether the user can delete the cost plan.
     */
    public function delete(User $user, BiayaReklamasi $rencanaBiaya): bool
    {
        return $user->isOwnerOfLahan($rencanaBiaya->lahan);
    }

    /**
     * Determine whether the user can export/generate PDF.
     */
    public function generatePDF(User $user, Lahan $lahan): bool
    {
        return $user->hasAccessToLahan($lahan);
    }
}