<?php

namespace App\Policies;

use App\Models\Plot;
use App\Models\Lahan;
use App\Models\User;

class PlotPolicy
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
     * Determine whether the user can view any plots for a lahan.
     */
    public function viewAny(User $user, Lahan $lahan): bool
    {
        // User can view plots if they have access to the lahan
        return $user->hasAccessToLahan($lahan);
    }

    /**
     * Determine whether the user can view the plot. 
     */
    public function view(User $user, Plot $plot): bool
    {
        // User can view if they have access to the lahan
        return $user->hasAccessToLahan($plot->lahan);
    }

    /**
     * Determine whether the user can create plots. 
     */
    public function create(User $user, Lahan $lahan): bool
    {
        // User can create if owner or editor of the lahan
        return $user->canEditLahan($lahan);
    }

    /**
     * Determine whether the user can update the plot. 
     */
    public function update(User $user, Plot $plot): bool
    {
        // User can update if owner or editor of the lahan
        return $user->canEditLahan($plot->lahan);
    }

    /**
     * Determine whether the user can delete the plot.
     */
    public function delete(User $user, Plot $plot): bool
    {
        // Only owner can delete plot
        return $user->isOwnerOfLahan($plot->lahan);
    }
}