<?php

namespace App\Policies;

use App\Models\TargetProgresReklamasi;
use App\Models\Plot;
use App\Models\User;

class TargetProgresReklamasiPolicy
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
     * Determine whether the user can view any target progres for a plot.
     */
    public function viewAny(User $user, Plot $plot): bool
    {
        // User can view if they have access to the lahan
        return $user->hasAccessToLahan($plot->lahan);
    }

    /**
     * Determine whether the user can view the target progres. 
     */
    public function view(User $user, TargetProgresReklamasi $target): bool
    {
        // User can view if they have access to the lahan
        return $user->hasAccessToLahan($target->plot->lahan);
    }

    /**
     * Determine whether the user can create/sync targets. 
     */
    public function manage(User $user, Plot $plot): bool
    {
        // User can manage targets if owner or editor of the lahan
        return $user->canEditLahan($plot->lahan);
    }

    /**
     * Determine whether the user can update the target progres.
     */
    public function update(User $user, TargetProgresReklamasi $target): bool
    {
        // User can update if owner or editor of the lahan
        return $user->canEditLahan($target->plot->lahan);
    }

    /**
     * Determine whether the user can delete the target progres.
     */
    public function delete(User $user, TargetProgresReklamasi $target): bool
    {
        // Only owner can delete target
        return $user->isOwnerOfLahan($target->plot->lahan);
    }
}