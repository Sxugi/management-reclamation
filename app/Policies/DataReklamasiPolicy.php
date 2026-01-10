<?php

namespace App\Policies;

use App\Models\DataReklamasi;
use App\Models\Lahan;
use App\Models\User;

class DataReklamasiPolicy
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
     * Determine whether the user can view the reklamasi list.
     */
    public function viewAny(User $user, Lahan $lahan): bool
    {
        return $user->hasAccessToLahan($lahan);
    }

    /**
     * Determine whether the user can create a reklamasi data.
     */
    public function create(User $user, Lahan $lahan): bool
    {
        return $user->canEditLahan($lahan);
    }

    /**
     * Determine whether the user can update the reklamasi data.
     */
    public function update(User $user, DataReklamasi $dataReklamasi): bool
    {
        return $user->canEditLahan($dataReklamasi->lahan);
    }

    /**
     * Determine whether the user can delete the reklamasi data.
     */
    public function delete(User $user, DataReklamasi $dataReklamasi): bool
    {
        return $user->isOwnerOfLahan($dataReklamasi->lahan);
    }

    /**
     * Determine whether the user can generate PDF.
     */
    public function generatePDF(User $user, Lahan $lahan): bool
    {
        return $user->hasAccessToLahan($lahan);
    }
}