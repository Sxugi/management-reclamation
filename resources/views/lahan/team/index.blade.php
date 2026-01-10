<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-row items-center justify-between gap-4">
            <h2 class="text-xl font-bold text-darkslategray font-outfit">Access Management</h2>
            <div class="self-stretch flex flex-row items-center justify-start gap-1.5 text-left text-sm text-slategray font-outfit">
                <a type="button" href="{{ route('lahan.index') }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">List Lahan</a>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <a type="button" href="{{ route('detail-lahan.dashboard', $lahan) }}" class="relative leading-5 text-darkslategray no-underline visited:text-darkslategray">{{ $lahan->nama_lahan }}</a>
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.83333 12.6667L10 8.5L5.83333 4.33333" stroke="#667085" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <div class="relative leading-5 text-darkslategray-200 font-medium">Access Management</div>
            </div>
        </div>
    </x-slot>

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div data-turbo-temporary class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div data-turbo-temporary class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="self-stretch flex flex-col items-start justify-start gap-6 font-outfit">
        {{-- Header Section --}}
        <div class="self-stretch flex flex-row flex-wrap items-center justify-between gap-4">
            <div class="flex flex-col gap-1">
                <h1 class="flex flex-row text-2xl font-bold text-darkslategray leading-7 gap-2">
                    <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M17.754 11C18.72 11 19.504 11.784 19.504 12.75V19.499C19.504 20.958 18.9244 22.3572 17.8928 23.3888C16.8612 24.4204 15.462 25 14.003 25C12.544 25 11.1448 24.4204 10.1132 23.3888C9.08157 22.3572 8.502 20.958 8.502 19.499V12.75C8.502 11.784 9.285 11 10.252 11H17.754ZM3.75 11L8.132 10.998C7.77165 11.4322 7.5547 11.9674 7.511 12.53L7.501 12.75V19.499C7.501 20.632 7.792 21.698 8.301 22.626C7.61593 22.9252 6.86712 23.049 6.12221 22.9862C5.3773 22.9235 4.65975 22.6761 4.0344 22.2665C3.40905 21.8569 2.89558 21.298 2.5404 20.6402C2.18521 19.9825 1.99948 19.2466 2 18.499V12.75C2 12.5201 2.0453 12.2925 2.13331 12.0801C2.22132 11.8677 2.35031 11.6747 2.51292 11.5122C2.67553 11.3497 2.86856 11.2208 3.081 11.1329C3.29343 11.045 3.5201 10.9999 3.75 11ZM19.875 10.998L24.25 11C25.216 11 26 11.784 26 12.75V18.5C26.0003 19.2472 25.8146 19.9826 25.4595 20.6401C25.1045 21.2975 24.5914 21.8562 23.9664 22.2657C23.3415 22.6752 22.6244 22.9227 21.8799 22.9857C21.1354 23.0488 20.3869 22.9255 19.702 22.627L19.758 22.525C20.187 21.712 20.448 20.796 20.496 19.825L20.504 19.499V12.75C20.504 12.084 20.268 11.474 19.875 10.998ZM14 3C14.4596 3 14.9148 3.09053 15.3394 3.26642C15.764 3.44231 16.1499 3.70012 16.4749 4.02513C16.7999 4.35013 17.0577 4.73597 17.2336 5.16061C17.4095 5.58525 17.5 6.04037 17.5 6.5C17.5 6.95963 17.4095 7.41475 17.2336 7.83939C17.0577 8.26403 16.7999 8.64987 16.4749 8.97487C16.1499 9.29988 15.764 9.55769 15.3394 9.73358C14.9148 9.90947 14.4596 10 14 10C13.0717 10 12.1815 9.63125 11.5251 8.97487C10.8688 8.3185 10.5 7.42826 10.5 6.5C10.5 5.57174 10.8688 4.6815 11.5251 4.02513C12.1815 3.36875 13.0717 3 14 3ZM22.003 4C22.397 4 22.7871 4.0776 23.1511 4.22836C23.515 4.37913 23.8457 4.6001 24.1243 4.87868C24.4029 5.15726 24.6239 5.48797 24.7746 5.85195C24.9254 6.21593 25.003 6.60603 25.003 7C25.003 7.39397 24.9254 7.78407 24.7746 8.14805C24.6239 8.51203 24.4029 8.84274 24.1243 9.12132C23.8457 9.3999 23.515 9.62087 23.1511 9.77164C22.7871 9.9224 22.397 10 22.003 10C21.2074 10 20.4443 9.68393 19.8817 9.12132C19.3191 8.55871 19.003 7.79565 19.003 7C19.003 6.20435 19.3191 5.44129 19.8817 4.87868C20.4443 4.31607 21.2074 4 22.003 4ZM5.997 4C6.39097 4 6.78107 4.0776 7.14505 4.22836C7.50903 4.37913 7.83975 4.6001 8.11832 4.87868C8.3969 5.15726 8.61788 5.48797 8.76864 5.85195C8.9194 6.21593 8.997 6.60603 8.997 7C8.997 7.39397 8.9194 7.78407 8.76864 8.14805C8.61788 8.51203 8.3969 8.84274 8.11832 9.12132C7.83975 9.3999 7.50903 9.62087 7.14505 9.77164C6.78107 9.9224 6.39097 10 5.997 10C5.20135 10 4.43829 9.68393 3.87568 9.12132C3.31307 8.55871 2.997 7.79565 2.997 7C2.997 6.20435 3.31307 5.44129 3.87568 4.87868C4.43829 4.31607 5.20135 4 5.997 4Z" fill="black"/>
                    </svg>    
                    Tim Pengelola Lahan
                </h1>
                <p class="text-sm text-black leading-5">Kelola anggota tim dan role mereka pada lahan ini</p>
            </div>
            
            <div class="flex flex-row items-center justify-center gap-2">
                <a href=" {{ route('lahan.index', $lahan->lahan_id) }} " class="text-sm rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 !text-white no-underline hover:bg-slategray-200">
                    <span class="relative leading-5 font-medium font-outfit">Back</span>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.33312 8.33335L2.74395 8.92251L2.15479 8.33335L2.74395 7.74418L3.33312 8.33335ZM17.4998 15C17.4998 15.221 17.412 15.433 17.2557 15.5893C17.0994 15.7456 16.8875 15.8333 16.6665 15.8333C16.4454 15.8333 16.2335 15.7456 16.0772 15.5893C15.9209 15.433 15.8331 15.221 15.8331 15H17.4998ZM6.91062 13.0892L2.74395 8.92251L3.92228 7.74418L8.08895 11.9108L6.91062 13.0892ZM2.74395 7.74418L6.91062 3.57751L8.08895 4.75585L3.92228 8.92251L2.74395 7.74418ZM3.33312 7.50001H11.6665V9.16668H3.33312V7.50001ZM17.4998 13.3333V15H15.8331V13.3333H17.4998ZM11.6665 7.50001C13.2135 7.50001 14.6973 8.1146 15.7912 9.20856C16.8852 10.3025 17.4998 11.7863 17.4998 13.3333H15.8331C15.8331 12.2283 15.3941 11.1685 14.6127 10.3871C13.8313 9.60567 12.7715 9.16668 11.6665 9.16668V7.50001Z" fill="white"/>
                    </svg>
                </a>
                @can('manageTeam', $lahan)
                    <button 
                        type="button"
                        x-data="" 
                        @click="$dispatch('open-modal', 'add-team-member')"
                        class="rounded-lg bg-darkslategray overflow-hidden flex flex-row items-center justify-center py-3 px-4 gap-2 ! text-white border-none cursor-pointer hover:bg-slategray-200 font-medium transition-colors">
                        <span class="relative leading-5 text-sm">Tambah Anggota</span>
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10 4.24951C10.4142 4.24951 10.75 4.58534 10.75 4.99951V9.24951H15.001L15.0771 9.25342C15.4553 9.29177 15.7508 9.61128 15.751 9.99951C15.751 10.3879 15.4554 10.7072 15.0771 10.7456L15.001 10.7495H10.75V15.0005L10.7461 15.0767C10.7077 15.4549 10.3884 15.7505 10 15.7505C9.61173 15.7504 9.29227 15.4548 9.25391 15.0767L9.25 15.0005V10.7495H5C4.58579 10.7495 4.25 10.4137 4.25 9.99951C4.25015 9.58543 4.58588 9.24951 5 9.24951H9.25V4.99951C9.25004 4.5854 9.58591 4.24962 10 4.24951Z" fill="white"/>
                        </svg>
                    </button>
                @endcan
            </div>
        </div>

        {{-- Your Role Info Card --}}
        <div class="self-stretch rounded-xl bg-blue-50 border border-blue-200 px-4 py-3">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-sm font-semibold text-darkslategray">Role Anda: </span>
                        @php
                            $roleBadge = match($userRole) {
                                'owner' => 'bg-blue-100 text-blue-800 border-blue-200',
                                'editor' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'viewer' => 'bg-orange-100 text-orange-800 border-orange-200',
                                default => 'bg-green-100 text-green-800 border-green-200'
                            };
                        @endphp
                        <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $roleBadge }}">
                            {{ $userRole ?  ucfirst($userRole) : 'Admin' }}
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-3 text-xs">
                        @can('view', $lahan)
                            <span class="text-green-600 font-medium">✅ View</span>
                        @else
                            <span class="text-gray-400">❌ View</span>
                        @endcan
                        @can('update', $lahan)
                            <span class="text-green-600 font-medium">✅ Edit</span>
                        @else
                            <span class="text-gray-400">❌ Edit</span>
                        @endcan
                        @can('delete', $lahan)
                            <span class="text-green-600 font-medium">✅ Delete</span>
                        @else
                            <span class="text-gray-400">❌ Delete</span>
                        @endcan
                        @can('manageTeam', $lahan)
                            <span class="text-green-600 font-medium">✅ Manage Team</span>
                        @else
                            <span class="text-gray-400">❌ Manage Team</span>
                        @endcan
                    </div>
                </div>
            </div>
        </div>

        {{-- Team Members Table --}}
        <div class="self-stretch rounded-2xl bg-white shadow-md border border-gainsboro overflow-hidden">
            <div class="px-6 py-4 border-b border-gainsboro">
                <div class="font-bold text-base text-darkslategray font-outfit">Anggota Tim</div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full w-full text-xs text-darkslategray font-outfit border-collapse table-auto">
                    <thead class="border-b border-gainsboro">
                        <tr>
                            <th class="py-3 px-6 text-left font-bold border-r border-gainsboro whitespace-nowrap">Nama</th>
                            <th class="py-3 px-6 text-left font-bold border-r border-gainsboro whitespace-nowrap">Email</th>
                            <th class="py-3 px-6 text-left font-bold border-r border-gainsboro whitespace-nowrap">Phone</th>
                            <th class="py-3 px-6 text-center font-bold border-r border-gainsboro whitespace-nowrap">Role</th>
                            <th class="py-3 px-6 text-center font-bold border-gainsboro whitespace-nowrap">Bergabung</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($team as $member)
                            @php
                                $roleBadgeColor = match($member->pivot->role) {
                                    'owner' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'editor' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'viewer' => 'bg-orange-100 text-orange-800 border-orange-200',
                                    default => 'bg-green-100 text-green-800 border-green-200'
                                };
                                
                                $memberData = [
                                    'user_id' => $member->user_id,
                                    'name' => $member->name,
                                    'username' => $member->username,
                                    'email' => $member->email,
                                    'phone' => $member->phone,
                                    'status' => $member->status,
                                    'role' => $member->pivot->role,
                                    'avatar' => $member->avatar ? asset('storage/' . $member->avatar) : null,
                                    'joined_at' => $member->pivot->created_at->toISOString(),
                                    'updated_at' => $member->pivot->updated_at->toISOString(),
                                    'remove_url' => route('lahan.team.destroy', [$lahan, $member])
                                ];
                            @endphp
                            
                            <tr class="border-b border-gainsboro hover:bg-lightgray transition-colors cursor-pointer"
                                onclick='window.openTeamMemberModal(@json($memberData))'>
                                <td class="py-3 px-6 border-r border-gainsboro">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex-shrink-0 overflow-hidden">
                                            @if($member->avatar)
                                                <img src="{{ asset('storage/' . $member->avatar) }}" 
                                                    alt="{{ $member->name }}" 
                                                    class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold text-sm">
                                                    {{ strtoupper(substr($member->name, 0, 2)) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-semibold text-darkslategray text-sm truncate">{{ $member->name }}</div>
                                            <div class="text-xs text-slategray truncate">{{ $member->username }}</div>
                                        </div>
                                    </div>
                                    {{-- Edit Role Modal (per member) --}}
                                    @can('manageTeam', $lahan)
                                        <x-main.modal name="edit-role-{{ $member->user_id }}" class="font-outfit" focusable>
                                            <form action="{{ route('lahan.team.update', [$lahan, $member]) }}" method="POST" class="p-6">
                                                @csrf
                                                @method('PUT')
                                                
                                                <h2 class="text-lg font-medium text-gray-900 mb-4">
                                                    Edit Role - {{ $member->name }}
                                                </h2>

                                                <div class="mb-3">
                                                    <x-main.input-label class="block text-sm font-medium text-darkslategray mb-2">Current Role</x-main.input-label>
                                                    <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold border {{ $roleBadgeColor }}">
                                                        {{ ucfirst($member->pivot->role) }}
                                                    </span>
                                                </div>

                                                <div class="mb-3">
                                                    <x-main.input-label for="role-{{ $member->user_id }}" class="block text-sm font-medium text-darkslategray mb-2">
                                                        New Role <span class="text-red-500">*</span>
                                                    </x-main.input-label>
                                                    <select name="role" id="role-{{ $member->user_id }}" required
                                                            class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent">
                                                        <option value="owner" {{ $member->pivot->role === 'owner' ? 'selected' : '' }}>
                                                            👑 Owner - Full Control
                                                        </option>
                                                        <option value="editor" {{ $member->pivot->role === 'editor' ? 'selected' : '' }}>
                                                            ✏️ Editor - Can Edit Data
                                                        </option>
                                                        <option value="viewer" {{ $member->pivot->role === 'viewer' ? 'selected' : '' }}>
                                                            👁️ Viewer - Read Only
                                                        </option>
                                                    </select>
                                                </div>

                                                <div class="rounded-lg bg-blue-50 border border-blue-200 p-4 text-sm text-gray mb-4">
                                                    <div class="font-semibold mb-2 text-darkslategray">📋 Permission Levels: </div>
                                                    <ul class="space-y-1 text-xs">
                                                        <li>• <strong>Owner: </strong> Full control + manage team</li>
                                                        <li>• <strong>Editor:</strong> Can edit lahan data</li>
                                                        <li>• <strong>Viewer: </strong> Read-only access</li>
                                                    </ul>
                                                </div>

                                                <div class="flex justify-end gap-3 font-outfit">
                                                    <a @click="$dispatch('close')" class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-medium hover:bg-red-600 transition-colors no-underline">
                                                        Cancel
                                                    </a>
                                                    <x-main.primary-button type="submit" class="py-3 px-4 gap-2 font-medium">
                                                        Update
                                                    </x-main.primary-button>
                                                </div>
                                            </form>
                                        </x-main.modal>
                                        {{-- Delete Confirmation Modal --}}
                                        <x-main.modal name="confirm-team-deletion-{{ $member->user_id }}" focusable>
                                            <form method="POST" action="{{ route('lahan.team.destroy', [$lahan, $member]) }}" class="p-6">
                                                @csrf
                                                @method('DELETE')
                                                
                                                <h2 class="text-lg font-medium text-gray-900">
                                                    {{ __('Are you sure you want to remove this team member?') }}
                                                </h2>
                                                
                                                <p class="mt-1 text-sm text-gray-600">
                                                    {{ __('This user will lose access to this lahan.  This action cannot be undone.') }}
                                                </p>

                                                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                                    <div class="flex items-start gap-2">
                                                        <svg class="w-5 h-5 text-yellow-600 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        <div class="flex-1">
                                                            <p class="text-sm font-semibold text-yellow-800">Team Member Details: </p>
                                                            <ul class="mt-1 text-sm text-yellow-700 space-y-1">
                                                                <li>• Name: <strong>{{ $member->name }}</strong></li>
                                                                <li>• Email: <strong>{{ $member->email }}</strong></li>
                                                                <li>• Role: <strong>{{ ucfirst($member->pivot->role) }}</strong></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="mt-6 flex justify-end gap-3 font-outfit">
                                                    <x-main.secondary-button x-on:click="$dispatch('close')">
                                                        {{ __('Cancel') }}
                                                    </x-main.secondary-button>
                                                    <x-main.danger-button type="submit">
                                                        {{ __('Remove from Team') }}
                                                    </x-main.danger-button>
                                                </div>
                                            </form>
                                        </x-main.modal>
                                    @endcan
                                </td>
                                <td class="py-3 px-6 text-sm text-gray leading-5 border-r border-gainsboro">{{ $member->email }}</td>
                                <td class="py-3 px-6 text-sm text-gray leading-5 border-r border-gainsboro">{{ $member->phone }}</td>
                                <td class="py-3 px-6 text-center border-r border-gainsboro">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold border {{ $roleBadgeColor }}">
                                        {{ ucfirst($member->pivot->role) }}
                                    </span>
                                </td>
                                <td class="py-3 px-6 text-center text-sm text-gray leading-5">
                                    {{ $member->pivot->created_at->format('d F Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <div class="flex flex-col items-center justify-center gap-3 text-slategray">
                                        <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="white" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            </svg>
                                        </div>
                                        <p class="font-medium text-sm">Belum ada anggota tim</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Team Summary Footer --}}
            @if($team->count() > 0)
                <div class="px-6 py-4 border-gainsboro">
                    <div class="grid grid-cols-4 gap-4 text-center">
                        <div>
                            <div class="text-2xl font-bold text-darkslategray">{{ $team->count() }}</div>
                            <div class="text-xs text-slategray font-medium">Total Anggota</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-blue-600">{{ $team->where('pivot.role', 'owner')->count() }}</div>
                            <div class="text-xs text-slategray font-medium">Owners</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-yellow-600">{{ $team->where('pivot.role', 'editor')->count() }}</div>
                            <div class="text-xs text-slategray font-medium">Editors</div>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-gray-600">{{ $team->where('pivot.role', 'viewer')->count() }}</div>
                            <div class="text-xs text-slategray font-medium">Viewers</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Add Team Member Modal --}}
    @can('manageTeam', $lahan)
        <x-main.modal name="add-team-member" class="font-outfit" focusable>
            <form action="{{ route('lahan.team.store', $lahan) }}" method="POST" class="p-6">
                @csrf
                
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    Tambah Anggota Tim
                </h2>

                <div class="space-y-4">
                    <div>
                        <x-main.input-label for="user_id" class="block text-sm font-medium text-darkslategray mb-2">
                            Pilih User <span class="text-red-500">*</span>
                        </x-main.input-label>
                        <select name="user_id" id="user_id" required
                                class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent">
                            <option value="">-- Pilih User --</option>
                            @forelse($availableUsers as $user)
                                <option value="{{ $user->user_id }}">
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @empty
                                <option value="" disabled>Semua user sudah ter-assign</option>
                            @endforelse
                        </select>
                        <p class="text-xs text-slategray mt-1">User yang belum ter-assign ke lahan ini</p>
                    </div>

                    <div>
                        <x-main.input-label for="role" class="block text-sm font-medium text-darkslategray mb-2">
                            Role <span class="text-red-500">*</span>
                        </x-main.input-label>
                        <select name="role" id="role" required
                                class="text-sm block w-full border-solid border-[1px] border-gray-300 focus:border-darkslategray focus:ring-darkslategray rounded-md px-3 py-2 box-border font-outfit flex-1 leading-5 bg-transparent">
                            <option value="viewer" selected>👁️ Viewer (Read Only)</option>
                            <option value="editor">✏️ Editor (Can Edit)</option>
                            <option value="owner">👑 Owner (Full Control)</option>
                        </select>
                    </div>

                    <div class="rounded-lg bg-blue-50 border border-blue-200 p-4 text-sm text-gray">
                        <div class="font-semibold mb-2 text-darkslategray">📋 Permission Levels: </div>
                        <ul class="space-y-1 text-xs">
                            <li>• <strong>Owner:</strong> Full control, can manage team</li>
                            <li>• <strong>Editor:</strong> Can edit lahan data</li>
                            <li>• <strong>Viewer:</strong> Read-only access</li>
                        </ul>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3 font-outfit">
                    <a @click="$dispatch('close')" class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-medium hover:bg-red-600 transition-colors no-underline">
                        Cancel
                    </a>
                    <x-main.primary-button type="submit" class="py-3 px-4 gap-2 font-medium">
                        Save
                    </x-main.primary-button>
                </div>
            </form>
        </x-main.modal>
    @endcan

    {{-- Include Detail Modal Component --}}
    <x-lahan.team.detail-modal :lahan="$lahan"/>
</x-app-layout>
