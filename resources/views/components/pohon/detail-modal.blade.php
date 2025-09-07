<div id="pohonModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gainsboro bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white">
                        <path d="M20 7V5C20 3.89543 19.1046 3 18 3H6C4.89543 3 4 3.89543 4 5V7M20 7H4M20 7V19C20 20.1046 19.1046 21 18 21H6C4.89543 21 6 20.1046 6 19V7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 11V17M15 11V17" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center self-stretch leading-7 font-semibold text-lg text-darkslategray font-outfit">Detail Data Pohon</div>
                    <div class="text-sm text-slategray font-outfit">Informasi lengkap data pohon</div>
                </div>
            </div>
            <button onclick="window.closePohonModal()" class="w-10 h-10 rounded-xl bg-white shadow-sm border border-gainsboro hover:bg-gray-500 flex items-center justify-center text-slategray hover:text-white transition-all duration-200">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="p-6 font-outfit">
            <div id="pohonModalContent" class="space-y-6">
                
            </div>
        </div>

        <div class="flex items-center justify-end p-6 border-t border-gainsboro bg-gray-50 rounded-b-2xl">
            <div class="flex items-center space-x-3">
                <button onclick="window.closePohonModal()" class="bg-red-500 !text-white text-sm py-3 px-4 rounded-lg font-medium font-outfit hover:bg-red-600 transition-colors no-underline border-none">
                    Close
                </button>
                <x-main.primary-button id="editPohonButton" class="py-3 px-4 gap-2 font-medium hidden">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 13V16H7L16 7L13 4L4 13Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Edit
                </x-main.primary-button>
                <x-main.primary-button id="deletePohonButton" class="py-3 px-4 gap-2 font-medium hidden">
                    <svg width="18" height="18" viewBox="0 0 15 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4.52679 1.0877L4.28571 1.5625H1.07143C0.478795 1.5625 0 2.0373 0 2.625C0 3.2127 0.478795 3.6875 1.07143 3.6875H13.9286C14.5212 3.6875 15 3.2127 15 2.625C15 2.0373 14.5212 1.5625 13.9286 1.5625H10.7143L10.4732 1.0877C10.2924 0.725781 9.92076 0.5 9.51562 0.5H5.48438C5.07924 0.5 4.70759 0.725781 4.52679 1.0877ZM13.9286 4.75H1.07143L1.78125 16.0059C1.83482 16.8459 2.53795 17.5 3.38504 17.5H11.615C12.4621 17.5 13.1652 16.8459 13.2187 16.0059L13.9286 4.75Z" fill="white"/>
                    </svg>
                    Delete
                </x-main.primary-button>
            </div>
        </div>
    </div>
</div>