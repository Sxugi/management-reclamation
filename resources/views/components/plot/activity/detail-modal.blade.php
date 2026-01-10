<div id="activityLogModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between p-6 border-b border-gainsboro bg-gradient-to-r from-blue-50 to-indigo-50 rounded-t-2xl">
            <div class="flex items-center space-x-3">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 21C9.7 21 7.696 20.2377 5.988 18.713C4.28 17.1883 3.30067 15.284 3.05 13H5.1C5.33333 14.7333 6.10433 16.1667 7.413 17.3C8.72167 18.4333 10.2507 19 12 19C13.95 19 15.6043 18.321 16.963 16.963C18.3217 15.605 19.0007 13.9507 19 12C18.9993 10.0493 18.3203 8.39533 16.963 7.038C15.6057 5.68067 13.9513 5.00133 12 5C10.85 5 9.775 5.26667 8.775 5.8C7.775 6.33333 6.93333 7.06667 6.25 8H9V10H3V4H5V6.35C5.85 5.28333 6.88767 4.45833 8.113 3.875C9.33833 3.29167 10.634 3 12 3C13.25 3 14.421 3.23767 15.513 3.713C16.605 4.18833 17.555 4.82967 18.363 5.637C19.171 6.44433 19.8127 7.39433 20.288 8.487C20.7633 9.57967 21.0007 10.7507 21 12C20.9993 13.2493 20.762 14.4203 20.288 15.513C19.814 16.6057 19.1723 17.5557 18.363 18.363C17.5537 19.1703 16.6037 19.812 15.513 20.288C14.4223 20.764 13.2513 21.0013 12 21ZM14.8 16.2L11 12.4V7H13V11.6L16.2 14.8L14.8 16.2Z" fill="white"/>
                    </svg>
                </div>
                <div>
                    <div class="flex items-center self-stretch leading-7 font-semibold text-lg text-darkslategray font-outfit">Log Activity Plot Lahan</div>
                </div>
            </div>
            <button onclick="window.closeActivityLogModal()" class="w-10 h-10 rounded-xl bg-white shadow-sm border border-gainsboro hover:bg-gray-500 flex items-center justify-center text-slategray hover:text-white transition-all duration-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="font-outfit">
            <div id="activityLogModalContent" class="flex-1 overflow-y-auto p-6">

            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-between p-6 border-t border-gainsboro bg-gray-50 rounded-b-2xl font-outfit">
            <span id="logPageInfo" class="text-sm text-gray-600">Page 1 of 1</span>
            <div class="flex items-center space-x-3">
                <div class="flex gap-2">
                    <x-main.primary-button id="prevLogPage" 
                            class="px-3 py-1 text-sm bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Previous
                    </x-main.primary-button>
                    <x-main.primary-button id="nextLogPage" 
                            class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Next
                    </x-main.primary-button>
                </div>
            </div>
        </div>
    </div>
</div>