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
            // If assigning as owner, demote existing owner
            if ($validated['role'] === 'owner') {
                // 1. Get current owner
                $currentOwner = $lahan->primaryOwner();
                
                // 2. Demote current owner to editor
                if ($currentOwner) {
                    $lahan->updateUserRole($currentOwner, 'editor');
                }

                // 3. Update lahan pic_id
                $lahan->pic_id = $user->user_id;
                $lahan->save();
            }

            $lahan->assignUser($user, $validated['role']);

            DB::commit();

            $message = $validated['role'] === 'owner' 
                ? "{$user->name} sekarang menjadi Owner baru (Owner lama menjadi Editor)!" 
                : "{$user->name} berhasil ditambahkan sebagai {$validated['role']}!";

            return redirect()
                ->route('lahan.team.index', $lahan)
                ->with('success', $message);

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

        if ($currentRole === $validated['role']) {
            return redirect()
                ->route('lahan.team.index', $lahan)
                ->with('error', "⚠️ {$user->name} sudah memiliki role {$validated['role']}!");
        }

        DB::beginTransaction();
        try {
            // If changing to owner, demote existing owner
            if ($validated['role'] === 'owner') {
                // 1. Get current owner
                $currentOwner = $lahan->primaryOwner();
                
                // 2. Demote current owner to editor (if not the same person)
                if ($currentOwner && $currentOwner->user_id !== $user->user_id) {
                    $lahan->updateUserRole($currentOwner, 'editor');
                }

                // 3. Update lahan pic_id
                $lahan->pic_id = $user->user_id;
                $lahan->save();
                
                // 4. Promote new owner
                $lahan->updateUserRole($user, 'owner');
                
                $message = "Ownership berhasil ditransfer ke {$user->name}. Anda/Owner lama kini menjadi Editor.";
            } 
            // Else if demoting owner to editor/viewer
            elseif ($currentRole === 'owner') {
                DB::rollBack();
                return redirect()
                    ->route('lahan.team.index', $lahan)
                    ->with('error', '❌ Owner tidak bisa turun jabatan. Silakan promosikan user lain menjadi Owner untuk memindahkan hak akses.');
            } 
            // Regular role update
            else {
                $lahan->updateUserRole($user, $validated['role']);
                $message = "Role {$user->name} diubah dari {$currentRole} ke {$validated['role']}!";
            }

            DB::commit();

            return redirect()
                ->route('lahan.team.index', $lahan)
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Failed to update user role', [
                'lahan_id' => $lahan->lahan_id,
                'user_id' => $user->user_id,
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
                ->with('error', '❌ Tidak bisa menghapus Owner. Silakan transfer ownership ke user lain terlebih dahulu!');
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