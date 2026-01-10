document.addEventListener("turbo:load", () => {
        
    /**
     * Toggle activity log dropdown visibility
     */
    window.toggleActivityLogDropdown = function() {
        const dropdown = document.getElementById('activityLogDropdown');
        if (!dropdown) {
            console.warn('Activity log dropdown element not found');
            return;
        }
        dropdown.classList.toggle('hidden');
    };

    /**
     * Hide dropdown when user clicks outside of it
     */
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('activityLogDropdown');
        const button = document.getElementById('activityLogDropdownButton');
        if (!dropdown || !button) return;
        
        // Check if click is outside both dropdown and button
        if (!dropdown.contains(event.target) && !button.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });
    
    /**
     * Open activity log modal for specific plot
     * @param {number} plotId - Plot ID to show logs for
     * @param {object} initialData - Optional initial data to display
     */
    window.openActivityLogModal = function(plotId, initialData = null) {
        // Store current plot ID and sort direction globally
        window.plotId = plotId;
        window.currentDirection = 'desc';
        
        const modal = document.getElementById('activityLogModal');
        if (!modal) return;
        
        // Show modal
        modal.classList.remove('hidden');

        // Load initial data or fetch from server
        if (initialData) {
            renderActivityLogModal(initialData, plotId);
        } else {
            fetchActivityLogPage(plotId, 1);
        }
    };

    /**
     * Handle date column sort toggle (asc/desc)
     */
    window.handleSortTanggal = function() {
        const plotId = window.plotId;
        // Toggle between ascending and descending
        const newDirection = window.currentDirection === 'desc' ? 'asc' : 'desc';
        window.currentDirection = newDirection;
        
        // Fetch data with new sort direction
        window.fetchActivityLogPage(plotId, 1, newDirection);
    };

    /**
     * Render activity log modal content with data
     * @param {object} data - Paginated activity log data
     * @param {number} plotId - Plot ID
     * @param {string} sortDirection - Sort direction ('asc' or 'desc')
     */
    function renderActivityLogModal(data, plotId, sortDirection = 'desc') {
        // Get modal content elements
        const content = document.getElementById('activityLogModalContent');
        const pageInfo = document.getElementById('logPageInfo');
        const prevBtn = document.getElementById('prevLogPage');
        const nextBtn = document.getElementById('nextLogPage');
        if (!content || !pageInfo || !prevBtn || !nextBtn) return;

        /**
         * Format date string to Indonesian locale
         */
        const formatDateTime = dateString => {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        };

        /**
         * Format time string to Indonesian locale
         */
        const formatTime = dateString => {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit'
            });
        };

        /**
         * Update sort icon rotation based on direction
         */
        function updateSortIcon(direction) {
            const icon = document.getElementById('sortTanggalIcon');
            if (!icon) return;
            
            if (direction === 'desc') {
                icon.classList.add('rotate-180');
            } else {
                icon.classList.remove('rotate-180');
            }
        }

        // Extract data from response
        const logs = data.data || [];
        const currentPage = data.current_page || 1;
        const lastPage = data.last_page || 1;

        // Render table content or empty state
        content.innerHTML = logs.length
            ? `
                <div class="flex flex-col">
                    <!-- Activity Log Table -->
                    <table class="min-w-max w-auto text-xs text-darkslategray font-outfit border-collapse table-auto">
                        <!-- Table Header -->
                        <thead class="border-gainsboro border-solid border-b border-t">
                            <tr>
                                <!-- Sortable Date Column -->
                                <th class="py-3 px-4 text-center font-bold border-gainsboro border-r whitespace-nowrap text-sm cursor-pointer transition group"
                                    onclick="window.handleSortTanggal()">
                                    <div class="flex justify-center items-center">
                                        <span>Tanggal</span>
                                        <!-- Sort Icon -->
                                        <svg
                                            id="sortTanggalIcon"
                                            width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            class="w-5 h-5 transition-transform duration-200 text-darkslategray ml-1 opacity-50 group-hover:opacity-100"
                                        >
                                            <path d="M4.79175 7.39581L10.0001 12.6041L15.2084 7.39581"
                                                stroke="darkslategray" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                    </div>
                                </th>
                                <th class="py-3 px-4 text-center font-bold border-gainsboro border-r whitespace-nowrap text-sm">Waktu</th>
                                <th class="py-3 px-4 text-center font-bold border-gainsboro border-r whitespace-nowrap text-sm">User</th>
                                <th class="py-3 px-4 text-center font-bold border-gainsboro border-r whitespace-nowrap text-sm">Table</th>
                                <th class="py-3 px-4 text-center font-bold border-gainsboro border-r whitespace-nowrap text-sm">Action</th>
                                <th class="py-3 px-4 text-center font-bold border-gainsboro whitespace-nowrap text-sm">Deskripsi</th>
                            </tr>
                        </thead>
                        <!-- Table Body -->
                        <tbody>
                            ${logs.map(log => `
                                <tr class="hover:bg-gray-50 border-gainsboro border-b">
                                    <!-- Date Cell -->
                                    <td class="py-3 px-4 text-sm text-center text-gray-700 border-gainsboro border-r whitespace-nowrap align-center">${formatDateTime(log.created_at)}</td>
                                    <!-- Time Cell -->
                                    <td class="py-3 px-4 text-sm text-center text-gray-700 border-gainsboro border-r whitespace-nowrap align-center">${formatTime(log.created_at)}</td>
                                    <!-- User Cell -->
                                    <td class="py-3 px-4 text-sm text-center text-gray-700 border-gainsboro border-r whitespace-nowrap capitalize align-center">${log.user_name || '-'}</td>
                                    <!-- Table Name Cell -->
                                    <td class="py-3 px-4 text-sm text-center text-gray-700 border-gainsboro border-r whitespace-nowrap capitalize align-center">${log.table_name || '-'}</td>
                                    <!-- Action Cell -->
                                    <td class="py-3 px-4 text-sm text-center text-gray-700 border-gainsboro border-r whitespace-nowrap capitalize align-center">${log.action || '-'}</td>
                                    <!-- Description Cell -->
                                    <td class="px-4 text-sm text-left text-gray-700 border-gainsboro align-center">
                                        <div class="max-w-xs truncate-4-lines">${log.description || `${log.table_name || 'Unknown Table'} #${log.record_id || 'N/A'}`}</div>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `
            : `
                <!-- Empty State -->
                <div class="py-8 text-center text-gray-400">
                    <div class="mb-4">
                        <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <p class="text-lg font-medium">Tidak ada activity log</p>
                    <p class="text-sm text-gray-500 mt-1">Belum ada aktivitas yang tercatat untuk plot ini</p>
                </div>
            `;

        // Update pagination info and controls
        pageInfo.textContent = `Page ${currentPage} of ${lastPage}`;
        prevBtn.disabled = currentPage <= 1;
        nextBtn.disabled = currentPage >= lastPage;

        // Set pagination button event handlers
        prevBtn.onclick = currentPage <= 1 ? null : () => fetchActivityLogPage(plotId, currentPage - 1);
        nextBtn.onclick = currentPage >= lastPage ? null : () => fetchActivityLogPage(plotId, currentPage + 1);

        // Update pagination button visual states
        prevBtn.classList.toggle('opacity-50', prevBtn.disabled);
        prevBtn.classList.toggle('cursor-not-allowed', prevBtn.disabled);
        nextBtn.classList.toggle('opacity-50', nextBtn.disabled);
        nextBtn.classList.toggle('cursor-not-allowed', nextBtn.disabled);

        // Update global state and sort icon
        window.currentDirection = sortDirection || 'desc';
        updateSortIcon(window.currentDirection);
    }

    /**
     * Fetch activity log data from server with pagination and sorting
     * @param {number} plotId - Plot ID to fetch logs for
     * @param {number} page - Page number to fetch
     * @param {string} sortDirection - Sort direction ('asc' or 'desc')
     */
    window.fetchActivityLogPage = function(plotId, page = 1, sortDirection = 'desc') {
        const content = document.getElementById('activityLogModalContent');
        
        // Show loading state
        if (content) {
            content.innerHTML = `
                <div class="flex flex-col">
                    <!-- Loading Table Structure -->
                    <table class="min-w-max w-full text-sm text-darkslategray font-outfit border-collapse table-auto">
                        <thead class="border-gainsboro border-solid border-b border-t">
                            <tr>
                                <th class="py-3 px-4 flex justify-center text-center font-bold border-gainsboro border-r whitespace-nowrap text-sm group">
                                    <span>Tanggal</span>
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="w-5 h-5 transition-transform duration-200 text-darkslategray ml-1 opacity-50 group-hover:opacity-100 {{ $currentDirection === 'desc' ? 'rotate-180' : '' }}">
                                        <path d="M4.79175 7.39581L10.0001 12.6041L15.2084 7.39581"
                                            stroke="darkslategray" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </th>
                                <th class="py-3 px-4 text-center font-bold border-gainsboro border-r whitespace-nowrap text-sm">Waktu</th>
                                <th class="py-3 px-4 text-center font-bold border-gainsboro border-r whitespace-nowrap text-sm">User</th>
                                <th class="py-3 px-4 text-center font-bold border-gainsboro border-r whitespace-nowrap text-sm">Table</th>
                                <th class="py-3 px-4 text-center font-bold border-gainsboro border-r whitespace-nowrap text-sm">Action</th>
                                <th class="py-3 px-4 text-center font-bold border-gainsboro whitespace-nowrap text-sm">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loading Spinner Row -->
                            <tr class="border-gainsboro border-b">
                                <td colspan="5" class="py-8 text-center">
                                    <div class="flex justify-center items-center space-x-2">
                                        <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span class="text-gray-600 text-sm">Memuat data...</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `;
        }

        // Fetch data from server
        fetch(`/plot/${plotId}/activity-logs?page=${page}&sort=${sortDirection}`)
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => renderActivityLogModal(data, plotId, sortDirection))
            .catch(error => {
                // Show error state
                if (content) {
                    content.innerHTML = `
                        <div class="py-8 text-center text-red-500">
                            <div class="mb-4">
                                <svg class="mx-auto h-12 w-12 text-red-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-lg font-medium mb-2">❌ Error memuat activity logs</p>
                            <p class="text-sm text-gray-600 mb-4">${error.message}</p>
                            <!-- Retry Button -->
                            <button onclick="window.fetchActivityLogPage(${plotId}, ${page})" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Coba Lagi
                            </button>
                        </div>
                    `;
                }
            });
    };

    // ===== MODAL CLOSE FUNCTIONALITY =====
    
    /**
     * Close activity log modal
     */
    window.closeActivityLogModal = function() {
        const modal = document.getElementById('activityLogModal');
        if (modal) modal.classList.add('hidden');
    };

    /**
     * Close modal when clicking on background overlay
     */
    document.addEventListener('click', function(e) {
        const modal = document.getElementById('activityLogModal');
        if (modal && !modal.classList.contains('hidden') && e.target === modal) {
            window.closeActivityLogModal();
        }
    });
    
    /**
     * Close modal when pressing Escape key
     */
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('activityLogModal');
            if (modal && !modal.classList.contains('hidden')) {
                window.closeActivityLogModal();
            }
        }
    });
});