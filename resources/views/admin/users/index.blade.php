<x-admin-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-darkslategray font-outfit">Daftar User</h2>
                <p class="text-sm text-slategray">
                    Kelola semua user dalam sistem
                </p>
            </div>
            <a href="{{ route('admin.users.create') }}" 
               class="text-sm rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 ! text-white no-underline hover:bg-slategray-200 font-medium transition-colors">
                <span class="relative leading-5">Tambah User</span>
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 4.24951C10.4142 4.24951 10.75 4.58534 10.75 4.99951V9.24951H15.001L15.0771 9.25342C15.4553 9.29177 15.7508 9.61128 15.751 9.99951C15.751 10.3879 15.4554 10.7072 15.0771 10.7456L15.001 10.7495H10.75V15.0005L10.7461 15.0767C10.7077 15.4549 10.3884 15.7505 10 15.7505C9.61173 15.7504 9.29227 15.4548 9.25391 15.0767L9.25 15.0005V10.7495H5C4.58579 10.7495 4.25 10.4137 4.25 9.99951C4.25015 9.58543 4.58588 9.24951 5 9.24951H9.25V4.99951C9.25004 4.5854 9.58591 4.24962 10 4.24951Z" fill="white"/>
                </svg>
            </a>
        </div>
    </x-slot>

    <div class="space-y-6 font-outfit">
        <!-- Messages -->
        @if(session('success'))
            <div data-turbo-temporary class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div data-turbo-temporary class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Filters -->
        <div class="bg-white rounded-xl shadow-md border border-gainsboro p-4">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                <div>
                    <x-main.input-label for="search" class="block text-sm font-medium text-darkslategray mb-2">Search</x-main.input-label>
                    <input type="text" 
                        name="search" 
                        id="search" 
                        value="{{ request('search') }}"
                        placeholder="Nama, username, email..."
                        class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent">
                </div>

                <div>
                    <x-main.input-label for="role" class="block text-sm font-medium text-darkslategray mb-2">Role</x-main.input-label>
                    <select name="role" 
                            id="role"
                            class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent">
                        <option value="">Semua Role</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>

                <div>
                    <x-main.input-label for="status" class="block text-sm font-medium text-darkslategray mb-2">Status</x-main.input-label>
                    <select name="status" 
                            id="status"
                            class="text-sm block w-full border-solid border-[1px] border-gray-300 focus: border-darkslategray focus: ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit" 
                            class="flex-1 text-sm rounded-lg bg-darkslategray text-white py-2 px-4 font-medium hover:bg-slategray-200 transition-colors">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="inline mr-1">
                            <path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0016 9.5 6.5 6.5 0 109.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z" fill="currentColor"/>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" 
                       class="inline-flex items-center justify-center p-2 rounded-lg border border-gray-300 text-darkslategray hover:bg-gainsboro transition-colors">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.65 6.35A7.958 7.958 0 0012 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08A5.99 5.99 0 0112 18c-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z" fill="currentColor"/>
                        </svg>
                    </a>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="self-stretch rounded-2xl bg-white shadow-md border border-gainsboro overflow-hidden">
            <div class="px-6 py-4 border-b border-gainsboro">
                <div class="font-bold text-base text-darkslategray font-outfit">Daftar User</div>
            </div>

            <div class="grid flex-1 self-stretch auto-cols-fr gap-y-8 overflow-hidden">
                <div class="flex flex-col overflow-x-auto">
                    <table class="min-w-max w-full text-xs text-darkslategray font-outfit border-collapse table-auto">
                        <thead class="border-b border-gainsboro">
                            <tr>
                                <th class="py-3 px-6 text-left font-bold border-r border-gainsboro whitespace-nowrap">User</th>
                                <th class="py-3 px-6 text-left font-bold border-r border-gainsboro whitespace-nowrap">Contact</th>
                                <th class="py-3 px-6 text-center font-bold border-r border-gainsboro whitespace-nowrap">Role</th>
                                <th class="py-3 px-6 text-center font-bold border-r border-gainsboro whitespace-nowrap">Status</th>
                                <th class="py-3 px-6 text-center font-bold border-gainsboro whitespace-nowrap">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gainsboro">
                            @forelse($users as $user)
                                @php
                                    $userData = [
                                        'user_id' => $user->user_id,
                                        'name' => $user->name,
                                        'username' => $user->username,
                                        'email' => $user->email,
                                        'phone' => $user->phone,
                                        'status' => $user->status,
                                        'role' => $user->role,
                                        'avatar' => $user->avatar ?   asset('storage/' .$user->avatar) : null,
                                        'created_at' => $user->created_at->toISOString(),
                                        'updated_at' => $user->updated_at->toISOString(),
                                        'show_url' => route('admin.users.show', $user),
                                        'edit_url' => route('admin.users.edit', $user),
                                        'delete_url' => route('admin.users.destroy', $user),
                                        'stats' => [
                                            'total_lahan' => $user->lahan()->count(),
                                            'owned_lahan' => $user->lahan()->wherePivot('role', 'owner')->count(),
                                            'team_member' => $user->lahan()->wherePivot('role', '!=', 'owner')->count(),
                                        ]
                                    ];
                                @endphp
                                
                                <tr class="border-gainsboro hover:bg-lightgray transition-colors cursor-pointer"
                                    onclick='window.openUserDetailModal(@json($userData))'>
                                    <td class="py-3 px-6 border-r border-gainsboro">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full flex-shrink-0 overflow-hidden">
                                                @if($user->avatar)
                                                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                                                        alt="{{ $user->name }}" 
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold text-sm">
                                                        {{ strtoupper(substr($user->name, 0, 2)) }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="min-w-0">
                                                <div class="font-semibold text-darkslategray text-sm truncate">{{ $user->name }}</div>
                                                <div class="text-xs text-slategray truncate">{{ $user->username }}</div>
                                            </div>
                                        </div>
                                        {{-- Delete Confirmation Modal (per user) --}}
                                        <x-main.modal name="confirm-user-deletion-{{ $user->user_id }}" focusable>
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="p-6">
                                                @csrf
                                                @method('DELETE')
                                                
                                                <h2 class="text-lg font-medium text-gray-900">
                                                    {{ __('Are you sure you want to delete this user?') }}
                                                </h2>
                                                
                                                <p class="mt-1 text-sm text-gray-600">
                                                    {{ __('Once deleted, all data related to this user will be permanently lost.This action cannot be undone.') }}
                                                </p>

                                                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                                    <div class="flex items-start gap-2">
                                                        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <div class="flex-1">
                                                            <p class="text-sm font-semibold text-yellow-800">User Details: </p>
                                                            <ul class="mt-1 text-sm text-yellow-700 space-y-1">
                                                                <li>• Name: <strong>{{ $user->name }}</strong></li>
                                                                <li>• Email: <strong>{{ $user->email }}</strong></li>
                                                                <li>• Role: <strong>{{ ucfirst($user->role) }}</strong></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-6 flex justify-end gap-3 font-outfit">
                                                    <x-main.secondary-button @click="$dispatch('close')">
                                                        {{ __('Cancel') }}
                                                    </x-main.secondary-button>
                                                    <x-main.danger-button type="submit">
                                                        {{ __('Delete') }}
                                                    </x-main.danger-button>
                                                </div>
                                            </form>
                                        </x-main.modal>
                                    </td>
                                    <td class="py-3 px-6 border-r border-gainsboro">
                                        <div class="text-sm text-darkslategray">{{ $user->email }}</div>
                                        <div class="text-xs text-slategray">{{ $user->phone ??  '-' }}</div>
                                    </td>
                                    <td class="py-3 px-6 text-center border-r border-gainsboro">
                                        @if($user->role === 'admin')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold border bg-purple-100 text-purple-800 border-purple-200">
                                                Admin
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold border bg-blue-100 text-blue-800 border-blue-200">
                                                User
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-center border-r border-gainsboro">
                                        @if($user->status === 'active')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold border bg-green-100 text-green-800 border-green-200">
                                                Active
                                            </span>
                                        @elseif($user->status === 'inactive')
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold border bg-yellow-100 text-yellow-800 border-yellow-200">
                                                Inactive
                                            </span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold border bg-red-100 text-red-800 border-red-200">
                                                Suspended
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-center text-sm text-gray leading-5 border-gainsboro">
                                        {{ $user->created_at->format('d F Y') }}
                                    </td>
                                </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center gap-3 text-slategray">
                                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <p class="font-medium text-sm">Tidak ada user ditemukan</p>
                                        <p class="text-xs">Coba ubah filter atau tambah user baru</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Summary Footer -->
            @if($users->count() > 0)
                <div class="px-6 py-4 border-t border-gainsboro">
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-darkslategray">{{ $users->total() }}</div>
                            <div class="text-xs text-slategray font-medium">Total Users</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-purple-600">{{ $users->where('role', 'admin')->count() }}</div>
                            <div class="text-xs text-slategray font-medium">Admins</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-green-600">{{ $users->where('status', 'active')->count() }}</div>
                            <div class="text-xs text-slategray font-medium">Active</div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gainsboro">
                {{ $users->links() }}
            </div>
            @endif
        </div>
        <x-admin.user.detail-modal />
    </div>
</x-admin-layout>