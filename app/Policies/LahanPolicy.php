<?php

namespace App\Policies;

use App\Models\Lahan;
use App\Models\User;

class LahanPolicy
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
     * Determine if user can view any lahan (list)
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view lahan list
        // (but they only see their assigned lahan via scope)
        return true;
    }

    /**
     * Determine if user can view specific lahan
     */
    public function view(User $user, Lahan $lahan): bool
    {
        // User can view if assigned to this lahan (any role)
        return $user->hasAccessToLahan($lahan);
    }

    /**
     * Determine if user can create lahan
     */
    public function create(User $user): bool
    {
        // Only admin can create new lahan (handled by before())
        // Regular users are ASSIGNED to lahan by admin
        return false;
    }

    /**
     * Determine if user can update lahan
     */
    public function update(User $user, Lahan $lahan): bool
    {
        // User can update if owner or editor
        return $user->canEditLahan($lahan);
    }

    /**
     * Determine if user can delete lahan
     */
    public function delete(User $user, Lahan $lahan): bool
    {
        // Only owner can delete (not editor or viewer)
        return $user->isOwnerOfLahan($lahan);
    }

    /**
     * Determine if user can restore deleted lahan
     */
    public function restore(User $user, Lahan $lahan): bool
    {
        // Only admin can restore (handled by before())
        return false;
    }

    /**
     * Determine if user can permanently delete lahan
     */
    public function forceDelete(User $user, Lahan $lahan): bool
    {
        // Only admin can force delete (handled by before())
        return false;
    }

    /**
     * Determine if user can manage team assignments
     */
    public function manageTeam(User $user, Lahan $lahan): bool
    {
        // Only owner can manage team (assign/remove users)
        return $user->isOwnerOfLahan($lahan);
    }

    /**
     * Determine if user can view team members
     */
    public function viewTeam(User $user, Lahan $lahan): bool
    {
        // Anyone with access can view team
        return $user->hasAccessToLahan($lahan);
    }

    /**
     * Determine if user can export lahan data
     */
    public function export(User $user, Lahan $lahan): bool
    {
        // Owner and editor can export their lahan
        return $user->canEditLahan($lahan);
    }
}