<?php

namespace App\Policies;

use App\Models\ProgresReklamasi;
use App\Models\Plot;
use App\Models\User;

class ProgresReklamasiPolicy
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
     * Determine whether the user can view any progres reklamasi for a plot.
     */
    public function viewAny(User $user, Plot $plot): bool
    {
        // User can view if they have access to the lahan
        return $user->hasAccessToLahan($plot->lahan);
    }

    /**
     * Determine whether the user can view the progres reklamasi. 
     */
    public function view(User $user, ProgresReklamasi $progres): bool
    {
        // User can view if they have access to the lahan
        return $user->hasAccessToLahan($progres->plot->lahan);
    }

    /**
     * Determine whether the user can create progres reklamasi.
     */
    public function create(User $user, Plot $plot): bool
    {
        // User can create if owner or editor of the lahan
        return $user->canEditLahan($plot->lahan);
    }

    /**
     * Determine whether the user can update the progres reklamasi.
     */
    public function update(User $user, ProgresReklamasi $progres): bool
    {
        // User can update if owner or editor of the lahan
        return $user->canEditLahan($progres->plot->lahan);
    }

    /**
     * Determine whether the user can delete the progres reklamasi.
     */
    public function delete(User $user, ProgresReklamasi $progres): bool
    {
        // Only owner can delete progres
        return $user->isOwnerOfLahan($progres->plot->lahan);
    }
}