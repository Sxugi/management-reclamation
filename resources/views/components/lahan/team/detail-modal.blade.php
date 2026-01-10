<div id="teamMemberModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between p-6 border-b border-gainsboro bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" stroke="currentColor"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center self-stretch leading-7 font-semibold text-lg text-darkslategray font-outfit">
                        Detail Anggota Tim
                    </div>
                    <div class="text-sm text-slategray font-outfit">Informasi lengkap anggota tim lahan</div>
                </div>
            </div>
            <button onclick="window.closeTeamMemberModal()" class="w-10 h-10 rounded-xl bg-white shadow-sm border border-gainsboro hover:bg-gray-500 flex items-center justify-center text-slategray hover:text-white transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Content (populated by JavaScript) --}}
        <div class="p-6 font-outfit">
            <div id="teamMemberModalContent" class="space-y-6">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end p-6 border-t border-gainsboro bg-gray-50 rounded-b-2xl">
            <div class="flex items-center space-x-3">
                <x-main.primary-button onclick="window.closeTeamMemberModal()" class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-medium hover:bg-red-600 transition-colors no-underline">
                    Close
                </x-main.primary-button>
                <x-main.primary-button id="editRoleButton" class="py-3 px-4 gap-2 font-medium" :disabled="! auth()->user()->can('manageTeam', $lahan)">
                    Edit
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 13V16H7L16 7L13 4L4 13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </x-main.primary-button>
                <x-main.primary-button id="removeUserButton" class="py-3 px-4 gap-2 font-medium hidden" :disabled="! auth()->user()->can('manageTeam', $lahan)">
                    Remove
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M16.6668 5.75831L14.2418 3.33331L10.0002 7.57498L5.7585 3.33331L3.3335 5.75831L7.57516 9.99998L3.3335 14.2416L5.7585 16.6666L10.0002 12.425L14.2418 16.6666L16.6668 14.2416L12.4252 9.99998L16.6668 5.75831Z" fill="white"/>
                    </svg>
                </x-main.primary-button>
            </div>
        </div>
    </div>
</div>