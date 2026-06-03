<?php

namespace App\Policies;

use App\Models\Lahan;
use App\Models\ReklamasiFile;
use App\Models\User;

class ReklamasiFilePolicy
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
     * Determine whether the user can view the file page/preview.
     */
    public function viewAny(User $user, Lahan $lahan): bool
    {
        return $user->hasAccessToLahan($lahan);
    }

    /**
     * Determine whether the user can preview a specific file.
     */
    public function view(User $user, Lahan $lahan): bool
    {
        return $user->hasAccessToLahan($lahan);
    }

    /**
     * Determine whether the user can upload a file.
     */
    public function create(User $user, Lahan $lahan): bool
    {
        return $user->canEditLahan($lahan);
    }

    /**
     * Determine whether the user can delete a file.
     */
    public function delete(User $user, Lahan $lahan): bool
    {
        return $user->isOwnerOfLahan($lahan);
    }
}