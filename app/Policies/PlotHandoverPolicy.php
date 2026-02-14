<?php

namespace App\Policies;

use App\Models\PlotHandover;
use App\Models\Plot;
use App\Models\User;

class PlotHandoverPolicy
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
     * Determine whether the user can view any handover for a plot.
     */
    public function viewAny(User $user, Plot $plot): bool
    {
        // User can view if they have access to the lahan
        return $user->hasAccessToLahan($plot->lahan);
    }

    /**
     * Determine whether the user can view the handover.
     */
    public function view(User $user, PlotHandover $handover): bool
    {
        // User can view if they have access to the lahan
        return $user->hasAccessToLahan($handover->plot->lahan);
    }

    /**
     * Determine whether the user can create handover.
     */
    public function create(User $user, Plot $plot): bool
    {
        // User can create if owner or editor of the lahan
        return $user->canEditLahan($plot->lahan);
    }

    /**
     * Determine whether the user can update the handover.
     */
    public function update(User $user, PlotHandover $handover): bool
    {
        // User can update if owner or editor of the lahan
        return $user->canEditLahan($handover->plot->lahan);
    }

    /**
     * Determine whether the user can delete the handover.
     */
    public function delete(User $user, PlotHandover $handover): bool
    {
        // Only owner can delete handover
        return $user->isOwnerOfLahan($handover->plot->lahan);
    }

    /**
     * Determine whether the user can delete files from handover.
     */
    public function deleteFiles(User $user, PlotHandover $handover): bool
    {
        // User can delete files if owner or editor of the lahan
        return $user->canEditLahan($handover->plot->lahan);
    }
}