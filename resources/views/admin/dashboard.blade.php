<x-admin-layout>
    <x-slot name="title">Admin Dashboard</x-slot>
    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="bg-white rounded-lg overflow-hidden">
            <div class="p-6">
                <h3 class="text-2xl font-bold text-darkslategray">
                    Selamat Datang, {{ auth()->user()->name }}! 👋
                </h3>
                <p class="mt-2 text-sm text-gray-600">
                    Ini adalah dashboard admin untuk mengelola sistem Management Reclamation.
                </p>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-darkslategray rounded-lg p-3">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V18c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-1.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05.02.01.03.03.04.04 1.14.83 1.93 1.94 1.93 3.41V18c0 .35-.07.69-.18 1H22c.55 0 1-.45 1-1v-1.5c0-2.33-4.67-3.5-7-3.5z" fill="white"/>
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Users
                            </dt>
                            <dd class="mt-1">
                                <div class="text-3xl font-bold text-darkslategray">
                                    {{ $stats['total_users'] }}
                                </div>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Users -->
            <div class="bg-white rounded-lg shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-lg p-3">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Active Users
                            </dt>
                            <dd class="mt-1">
                                <div class="text-3xl font-bold text-green-600">
                                    {{ $stats['active_users'] }}
                                </div>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Admins -->
            <div class="bg-white rounded-lg shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-lg p-3">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z" fill="white"/>
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Total Admins
                            </dt>
                            <dd class="mt-1">
                                <div class="text-3xl font-bold text-purple-600">
                                    {{ $stats['total_admins'] }}
                                </div>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Inactive Users -->
            <div class="bg-white rounded-lg shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-yellow-500 rounded-lg p-3">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z" fill="white"/>
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Inactive Users
                            </dt>
                            <dd class="mt-1">
                                <div class="text-3xl font-bold text-yellow-600">
                                    {{ $stats['inactive_users'] }}
                                </div>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Suspended Users -->
            <div class="bg-white rounded-lg shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-red-500 rounded-lg p-3">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 11c-.55 0-1-.45-1-1V8c0-.55.45-1 1-1s1 .45 1 1v4c0 .55-.45 1-1 1zm1 4h-2v-2h2v2z" fill="white"/>
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Suspended Users
                            </dt>
                            <dd class="mt-1">
                                <div class="text-3xl font-bold text-red-600">
                                    {{ $stats['suspended_users'] }}
                                </div>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Regular Users -->
            <div class="bg-white rounded-lg shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-lg p-3">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="white"/>
                            </svg>
                        </div>
                        <div class="ml-5 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Regular Users
                            </dt>
                            <dd class="mt-1">
                                <div class="text-3xl font-bold text-blue-600">
                                    {{ $stats['total_regular_users'] }}
                                </div>
                            </dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow-[0px_1px_2px_rgba(16,_24,_40,_0.05)] overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-darkslategray mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">
                    <a href="{{ route('admin.users.create') }}" 
                    class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-darkslategray hover:bg-gainsboro transition-colors no-underline">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-darkslategray mb-2">
                            <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V8c0-.55-.45-1-1-1s-1 .45-1 1v2H2c-.55 0-1 .45-1 1s.45 1 1 1h2v2c0 .55.45 1 1 1s1-.45 1-1v-2h2c.55 0 1-.45 1-1s-.45-1-1-1H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" fill="currentColor"/>
                        </svg>
                        <span class="text-sm font-medium text-darkslategray">Tambah User</span>
                    </a>

                    <a href="{{ route('admin.users.index') }}" 
                    class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-darkslategray hover:bg-gainsboro transition-colors no-underline">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-darkslategray mb-2">
                            <path d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm0-8h2V7H3v2zm4 4h14v-2H7v2zm0 4h14v-2H7v2zM7 7v2h14V7H7z" fill="currentColor"/>
                        </svg>
                        <span class="text-sm font-medium text-darkslategray">List Users</span>
                    </a>

                    <a href="{{ route('admin.users.index', ['status' => 'active']) }}" 
                    class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-darkslategray hover:bg-gainsboro transition-colors no-underline">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-green-600 mb-2">
                            <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z" fill="currentColor"/>
                        </svg>
                        <span class="text-sm font-medium text-darkslategray">Active Users</span>
                    </a>

                    <a href="{{ route('admin.users.index', ['role' => 'admin']) }}" 
                    class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-gray-300 rounded-lg hover:border-darkslategray hover:bg-gainsboro transition-colors no-underline">
                        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-purple-600 mb-2">
                            <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4z" fill="currentColor"/>
                        </svg>
                        <span class="text-sm font-medium text-darkslategray">Admin Users</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>