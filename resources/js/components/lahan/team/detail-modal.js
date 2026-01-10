document.addEventListener("turbo:load", () => {
    /**
     * Open team member detail modal
     * @param {object} data - Team member data
     */
    window.openTeamMemberModal = (data) => {
        const modal = document.getElementById('teamMemberModal');
        const content = document.getElementById('teamMemberModalContent');
        const editButton = document.getElementById('editRoleButton');
        const removeButton = document.getElementById('removeUserButton');

        if (!modal || !content) {
            console.error('Team member modal elements not found');
            return;
        }

        /**
         * Format date time for display
         */
        const formatDateTime = (dateString) => {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        };

        /**
         * Get role badge styling
         */
        const getRoleBadge = (role) => {
            const badges = {
                'owner': { bg: 'bg-blue-100', text: 'text-blue-800', border: 'border-blue-200', emoji: '👑' },
                'editor': { bg: 'bg-yellow-100', text:  'text-yellow-800', border: 'border-yellow-200', emoji: '✏️' },
                'viewer': { bg: 'bg-orange-100', text: 'text-orange-800', border: 'border-orange-200', emoji:  '👁️' }
            };
            return badges[role] || badges['viewer'];
        };

        const roleBadge = getRoleBadge(data.role);

        // Build modal content with innerHTML
        content.innerHTML = `
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 font-outfit">
                <!-- User Information Card -->
                <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg space-y-4">
                    <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg width="14" height="14" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 7.5C9.34472 7.5 9.68606 7.4321 10.0045 7.30018C10.323 7.16827 10.6124 6.97491 10.8562 6.73116C11.0999 6.4874 11.2933 6.19802 11.4252 5.87954C11.5571 5.56106 11.625 5.21972 11.625 4.875C11.625 4.53028 11.5571 4.18894 11.4252 3.87046C11.2933 3.55198 11.0999 3.2626 10.8562 3.01884C10.6124 2.77509 10.323 2.58173 10.0045 2.44982C9.68606 2.3179 9.34472 2.25 9 2.25C8.30381 2.25 7.63613 2.52656 7.14384 3.01884C6.65156 3.51113 6.375 4.17881 6.375 4.875C6.375 5.57119 6.65156 6.23887 7.14384 6.73116C7.63613 7.22344 8.30381 7.5 9 7.5ZM2.25 15.3V15.75H15.75V15.3C15.75 13.62 15.75 12.78 15.423 12.138C15.1354 11.5735 14.6765 11.1146 14.112 10.827C13.47 10.5 12.63 10.5 10.95 10.5H7.05C5.37 10.5 4.53 10.5 3.888 10.827C3.32354 11.1146 2.86462 11.5735 2.577 12.138C2.25 12.78 2.25 13.62 2.25 15.3Z" fill="currentColor" stroke="currentColor" stroke-width="1.66667" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        Informasi User
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex items-start">
                            <div class="w-24 h-24 rounded-full bg-gradient-to-br from-blue-400 to-purple-500 flex items-center justify-center text-white font-bold text-2xl mr-4">
                                ${data.avatar ? `<img src="${data.avatar}" alt="${data.name}" class="w-full h-full object-cover rounded-full">` : `${data.name.substring(0, 2).toUpperCase()}`}
                            </div>
                            <div class="flex-1">
                                <div class="text-lg font-bold text-darkslategray">${data.name}</div>
                                <div class="text-sm text-slategray">@${data.username}</div>
                                <div class="mt-2">
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold ${roleBadge.bg} ${roleBadge.text} border ${roleBadge.border}">
                                        ${roleBadge.emoji} ${data.role.charAt(0).toUpperCase() + data.role.slice(1)}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="pt-3 border-t border-gainsboro">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-slategray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm font-medium text-darkslategray">Email</span>
                            </div>
                            <div class="text-sm text-gray ml-6">${data.email}</div>
                        </div>

                        <div class="pt-3 border-t border-gainsboro">
                            <div class="flex items-center gap-2 mb-2">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.1369 10.1148C8.32241 11.929 6.11259 13.3291 4.31098 13.3291C3.50098 13.3291 2.7917 13.0465 2.22036 12.4187C1.88768 12.0482 1.68036 11.6151 1.68036 11.1884C1.68036 10.8745 1.79982 10.5731 2.10089 10.3596L4.02223 8.9909C4.31714 8.79001 4.56197 8.68956 4.78804 8.68956C5.07679 8.68956 5.32804 8.85295 5.61679 9.13527L6.0625 9.57483C6.09368 9.60642 6.13079 9.63156 6.17169 9.6488C6.2126 9.66603 6.2565 9.67503 6.30089 9.67527C6.40134 9.67527 6.48947 9.63777 6.55831 9.60617C6.94134 9.39911 7.6067 8.82777 8.2284 8.21251C8.84366 7.59724 9.415 6.93188 9.6159 6.54268C9.65917 6.46587 9.68281 6.37957 9.68473 6.29143C9.68473 6.21001 9.65982 6.12804 9.59072 6.0592L9.15143 5.60117C8.86857 5.31215 8.70545 5.0609 8.70545 4.77215C8.70545 4.54634 8.8059 4.30152 9.01295 4.00634L10.3629 2.10402C10.5826 1.80268 10.8901 1.6709 11.2292 1.6709C11.6436 1.6709 12.0767 1.8592 12.4407 2.21733C13.0498 2.80126 13.3198 3.52286 13.3198 4.32001C13.3198 6.12188 11.9449 8.30679 10.1369 10.1148Z" fill="black"/>
                                </svg>
                                <span class="text-sm font-medium text-darkslategray">Phone</span>
                            </div>
                            <div class="text-sm text-gray ml-6">${data.phone || '-'}</div>
                        </div>

                        <div class="pt-3 border-t border-gainsboro">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-slategray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-sm font-medium text-darkslategray">Status</span>
                            </div>
                            <div class="text-sm ml-6">
                                <span class="px-2 py-1 rounded-full text-xs font-medium ${data.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                                    ${data.status === 'active' ? '✅ Active' : '❌ Inactive'}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role & Permissions Card -->
                <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg space-y-4">
                    <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" class="text-purple-600">
                                <path d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        Role & Permissions
                    </h3>

                    <div class="space-y-3">
                        <div class="p-4 bg-blue-50 rounded-lg border border-blue-100">
                            <div class="text-sm font-medium text-darkslategray mb-2">Current Role</div>
                            <div class="text-2xl font-bold ${roleBadge.text}">
                                ${roleBadge.emoji} ${data.role.charAt(0).toUpperCase() + data.role.slice(1)}
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="text-sm font-medium text-darkslategray">Permissions: </div>
                            ${data.role === 'owner' ? `
                                <div class="flex items-center gap-2 text-sm text-green-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    View lahan details
                                </div>
                                <div class="flex items-center gap-2 text-sm text-green-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Edit lahan data
                                </div>
                                <div class="flex items-center gap-2 text-sm text-green-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Delete lahan
                                </div>
                                <div class="flex items-center gap-2 text-sm text-green-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Manage team members
                                </div>
                            ` : data.role === 'editor' ?  `
                                <div class="flex items-center gap-2 text-sm text-green-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    View lahan details
                                </div>
                                <div class="flex items-center gap-2 text-sm text-green-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Edit lahan data
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-400">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Delete lahan
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-400">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Manage team members
                                </div>
                            ` : `
                                <div class="flex items-center gap-2 text-sm text-green-600">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    View lahan details (Read Only)
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-400">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Edit lahan data
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-400">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Delete lahan
                                </div>
                                <div class="flex items-center gap-2 text-sm text-gray-400">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Manage team members
                                </div>
                            `}
                        </div>

                        <div class="pt-3 border-t border-gainsboro">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-slategray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-sm font-medium text-darkslategray">Bergabung sejak</span>
                            </div>
                            <div class="text-sm text-gray ml-6">${formatDateTime(data.joined_at)}</div>
                        </div>

                        ${data.updated_at ? `
                        <div class="pt-3 border-t border-gainsboro">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-4 h-4 text-slategray" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                <span class="text-sm font-medium text-darkslategray">Last updated</span>
                            </div>
                            <div class="text-sm text-gray ml-6">${formatDateTime(data.updated_at)}</div>
                        </div>
                        ` : ''}
                    </div>
                </div>
            </div>
        `;

        // Setup edit button - Open edit role modal
        if (editButton) {
            editButton.onclick = () => {
                // Close detail modal
                window.closeTeamMemberModal();
                
                // Open edit role modal (Alpine.js)
                window.dispatchEvent(
                    new CustomEvent('open-modal', { 
                        detail: `edit-role-${data.user_id}` 
                    })
                );
            };
        }


        // Setup remove button
        if (removeButton && data.user_id) {
            removeButton.onclick = () => {
                // Close detail modal
                window.closeTeamMemberModal();
                // Open delete confirmation modal (Alpine.js)
                setTimeout(() => {
                    window.dispatchEvent(
                        new CustomEvent('open-modal', { 
                            detail: `confirm-team-deletion-${data.user_id}` 
                        })
                    );
                }, 300);
            };
        }

        // Show modal
        modal.classList.remove('hidden');
    };

    /**
     * Close team member modal
     */
    window.closeTeamMemberModal = () => {
        const modal = document.getElementById('teamMemberModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Close modal on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            window.closeTeamMemberModal();
        }
    });

    // Close modal on backdrop click
    document.addEventListener('click', (e) => {
        const modal = document.getElementById('teamMemberModal');
        if (modal && ! modal.classList.contains('hidden') && e.target === modal) {
            window.closeTeamMemberModal();
        }
    });
});