document.addEventListener("turbo:load", () => {
    /**
     * Open user detail modal
     * @param {object} data - User data
     */
    window.openUserDetailModal = (data) => {
        const modal = document.getElementById('userDetailModal');
        const content = document.getElementById('userDetailModalContent');
        const editButton = document.getElementById('editButton');
        const deleteButton = document.getElementById('deleteButton');

        if (!modal || !content) {
            console.error('User detail modal elements not found');
            return;
        }

        /**
         * Format date time for display
         */
        const formatDateTime = (dateString) => {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        };

        /**
         * Get role badge styling
         */
        const getRoleBadge = (role) => {
            const badges = {
                'admin': { bg: 'bg-purple-100', text: 'text-purple-800', border: 'border-purple-200', emoji: '👑' },
                'user':  { bg: 'bg-blue-100', text: 'text-blue-800', border: 'border-blue-200', emoji: '👤' }
            };
            return badges[role] || badges['user'];
        };

        /**
         * Get status badge styling
         */
        const getStatusBadge = (status) => {
            const badges = {
                'active': { text: 'Active', color: 'text-green-600' },
                'inactive': { text: 'Inactive', color:  'text-yellow-600' },
                'suspended': { text: 'Suspended', color: 'text-red-600' }
            };
            return badges[status] || badges['inactive'];
        };

        const roleBadge = getRoleBadge(data.role);
        const statusBadge = getStatusBadge(data.status);

        // Build modal content with innerHTML
        content.innerHTML = `
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 font-outfit">
                <!-- Left:  User Information Card -->
                <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-sm space-y-5">
                    <!-- Header with Icon -->
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-darkslategray">Informasi User</h3>
                    </div>

                    <!-- Avatar & Identity -->
                    <div class="flex items-start gap-4">
                        <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold text-2xl flex-shrink-0">
                            ${data.avatar ? `<img src="${data.avatar}" alt="${data.name}" class="w-full h-full object-cover rounded-full">` : `${data.name.substring(0, 2).toUpperCase()}`}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="text-lg font-bold text-darkslategray truncate">${data.name}</div>
                            <div class="text-sm text-slategray truncate">@${data.username}</div>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${roleBadge.bg} ${roleBadge.text} border ${roleBadge.border}">
                                    ${roleBadge.emoji} ${data.role.charAt(0).toUpperCase() + data.role.slice(1)}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Details -->
                    <div class="space-y-4 pt-4">
                        <!-- Email -->
                        <div>
                            <div class="text-sm font-semibold text-darkslategray mb-1">Email</div>
                            <div class="text-sm text-gray break-all">${data.email}</div>
                        </div>

                        <!-- Phone -->
                        <div>
                            <div class="text-sm font-semibold text-darkslategray mb-1">Phone</div>
                            <div class="text-sm text-gray">${data.phone || '-'}</div>
                        </div>
                    </div>
                </div>

                <!-- Right: Role & Status Card -->
                <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-sm space-y-5">
                    <!-- Header with Icon -->
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-semibold text-darkslategray">Role & Status</h3>
                    </div>

                    <!-- Role System Box -->
                    <div class="p-4 rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200">
                        <div class="text-sm font-medium text-darkslategray mb-2">Role Sistem</div>
                        <div class="text-3xl font-bold text-blue-700">
                            ${data.role.toUpperCase()}
                        </div>
                    </div>

                    <!-- Meta Information -->
                    <div class="space-y-3 text-sm">
                        <!-- Status -->
                        <div>
                            <span class="font-semibold text-darkslategray">Status:</span>
                            <span class="ml-2 ${statusBadge.color} font-medium">
                                ${statusBadge.text}
                            </span>
                        </div>

                        <!-- Bergabung -->
                        <div>
                            <span class="font-semibold text-darkslategray">Bergabung:</span>
                            <span class="ml-2 text-gray">
                                ${formatDateTime(data.created_at)}
                            </span>
                        </div>

                        <!-- Update Terakhir -->
                        <div>
                            <span class="font-semibold text-darkslategray">Update Terakhir:</span>
                            <span class="ml-2 text-gray">
                                ${formatDateTime(data.updated_at)}
                            </span>
                        </div>

                        ${data.stats ? `
                        <!-- Stats -->
                        <div class="pt-3 border-t border-gainsboro">
                            <div class="font-semibold text-darkslategray mb-3">Statistik Lahan</div>
                            <div class="grid grid-cols-3 gap-2">
                                <div class="text-center p-2 bg-blue-50 rounded-lg border border-blue-100">
                                    <div class="text-xl font-bold text-blue-600">${data.stats.total_lahan || 0}</div>
                                    <div class="text-xs text-slategray mt-1">Total</div>
                                </div>
                                <div class="text-center p-2 bg-green-50 rounded-lg border border-green-100">
                                    <div class="text-xl font-bold text-green-600">${data.stats.owned_lahan || 0}</div>
                                    <div class="text-xs text-slategray mt-1">Owned</div>
                                </div>
                                <div class="text-center p-2 bg-purple-50 rounded-lg border border-purple-100">
                                    <div class="text-xl font-bold text-purple-600">${data.stats.team_member || 0}</div>
                                    <div class="text-xs text-slategray mt-1">Member</div>
                                </div>
                            </div>
                        </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;

        if (editButton) {
            editButton.onclick = () => {
                // Close detail modal
                window.closeUserDetailModal();

                // Open edit user page
                window.location.href = `/admin/users/${data.user_id}/edit`;
            };
        }

        // Setup delete button
        if (deleteButton) {
            deleteButton.onclick = () => {
                // Close detail modal
                window.closeUserDetailModal();
                
                // Open delete confirmation modal (Alpine.js)
                setTimeout(() => {
                    window.dispatchEvent(
                        new CustomEvent('open-modal', { 
                            detail: `confirm-user-deletion-${data.user_id}` 
                        })
                    );
                }, 300);
            };
        }

        // Show modal
        modal.classList.remove('hidden');
    };

    /**
     * Close user detail modal
     */
    window.closeUserDetailModal = () => {
        const modal = document.getElementById('userDetailModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Close modal on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            window.closeUserDetailModal();
        }
    });

    // Close modal on backdrop click
    document.addEventListener('click', (e) => {
        const modal = document.getElementById('userDetailModal');
        if (modal && ! modal.classList.contains('hidden') && e.target === modal) {
            window.closeUserDetailModal();
        }
    });
});