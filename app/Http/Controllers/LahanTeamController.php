<?php

namespace App\Http\Controllers;

use App\Models\Lahan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LahanTeamController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display team members for lahan
     */
    public function index(Lahan $lahan)
    {
        $this->authorize('viewTeam', $lahan);

        $team = $lahan->users()
            ->withPivot(['role', 'created_at', 'updated_at']) 
            ->orderBy('lahan_user.role')
            ->orderBy('name') 
            ->get();

        $availableUsers = collect();

        if (auth()->user()->can('manageTeam', $lahan)) {
            $assignedUserIds = $lahan->users()->pluck('lahan_user.user_id');
            
            $availableUsers = User::active()
                ->whereNotIn('user_id', $assignedUserIds)
                ->where('role', 'user')
                ->orderBy('name')
                ->get();
        }

        $userRole = $lahan->getUserRole(auth()->user());

        return view('lahan.team.index', compact('lahan', 'team', 'userRole', 'availableUsers'));
    }

    /**
     * Assign user to lahan
     */
    public function store(Request $request, Lahan $lahan)
    {
        $this->authorize('manageTeam', $lahan);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'role' => 'required|in:owner,editor,viewer',
        ]);

        $user = User::findOrFail($validated['user_id']);

        if ($lahan->users()->where('lahan_user.user_id', $user->user_id)->exists()) {
            return redirect()
                ->route('lahan.team.index', $lahan)
                ->with('error', "❌ {$user->name} sudah ter-assign ke lahan ini!");
        }

        DB::beginTransaction();
        try {
            $lahan->assignUser($user, $validated['role']);

            DB::commit();

            return redirect()
                ->route('lahan.team.index', $lahan)
                ->with('success', "{$user->name} berhasil ditambahkan sebagai {$validated['role']}!");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to assign user to lahan', [
                'lahan_id' => $lahan->lahan_id,
                'user_id' => $user->user_id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()
                ->route('lahan.team.index', $lahan)
                ->with('error', 'Gagal menambahkan user: ' . $e->getMessage());
        }
    }

    /**
     * Update user's role
     */
    public function update(Request $request, Lahan $lahan, User $user)
    {
        $this->authorize('manageTeam', $lahan);

        $validated = $request->validate([
            'role' => 'required|in:owner,editor,viewer',
        ]);

        if (!$lahan->users()->where('lahan_user.user_id', $user->user_id)->exists()) {
            return redirect()
                ->route('lahan.team.index', $lahan)
                ->with('error', '❌ User tidak ter-assign ke lahan ini!');
        }

        $currentRole = $lahan->getUserRole($user);

        if ($currentRole === 'owner' && $lahan->owners()->count() === 1 && $validated['role'] !== 'owner') {
            return redirect()
                ->route('lahan.team.index', $lahan)
                ->with('error', '❌ Tidak bisa mengubah role owner terakhir!');
        } 

        if ($currentRole === $validated['role']) {
            return redirect()
                ->route('lahan.team.index', $lahan)
                ->with('error', "⚠️ {$user->name} sudah memiliki role {$validated['role']}!");
        }

        DB::beginTransaction();
        try {
            $oldRole = $lahan->getUserRole($user);
            $lahan->updateUserRole($user, $validated['role']);

            DB::commit();

            return redirect()
                ->route('lahan.team.index', $lahan)
                ->with('success', "Role {$user->name} diubah dari {$oldRole} ke {$validated['role']}!");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update user role', [
                'lahan_id' => $lahan->lahan_id,
                'user_id' => $user->user_id,
                'old_role' => $currentRole,
                'new_role' => $validated['role'],
                'error' => $e->getMessage()
            ]);
            
            return redirect()
                ->route('lahan.team.index', $lahan)
                ->with('error', 'Gagal mengubah role: ' . $e->getMessage());
        }
    }

    /**
     * Remove user from lahan
     */
    public function destroy(Lahan $lahan, User $user)
    {
        $this->authorize('manageTeam', $lahan);

        if (!$lahan->users()->where('lahan_user.user_id', $user->user_id)->exists()) {
            return redirect()
                ->route('lahan. team.index', $lahan)
                ->with('error', '❌ User tidak ter-assign ke lahan ini!');
        }

        $userRole = $lahan->getUserRole($user);

        if ($userRole === 'owner' && $lahan->owners()->count() <= 1) {
            return redirect()
                ->route('lahan.team.index', $lahan)
                ->with('error', '❌ Tidak bisa menghapus owner terakhir!');
        }

        DB::beginTransaction();
        try {
            $userName = $user->name;
            $lahan->removeUser($user);

            DB::commit();

            return redirect()
                ->route('lahan.team.index', $lahan)
                ->with('success', "{$userName} berhasil dihapus dari tim!");

        } catch (\Exception $e) {
            DB::rollBack();
            \Log:: error('Failed to remove user from lahan', [
                'lahan_id' => $lahan->lahan_id,
                'user_id' => $user->user_id,
                'error' => $e->getMessage()
            ]);
            
            return redirect()
                ->route('lahan.team.index', $lahan)
                ->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}