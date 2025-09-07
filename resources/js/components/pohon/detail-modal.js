document.addEventListener("turbo:load", () => {
    // Open modal, with robust element selection
    window.openPohonModal = (data) => {
        const modal = document.getElementById('pohonModal');
        const content = document.getElementById('pohonModalContent');
        const editButton = document.getElementById('editPohonButton');
        const deleteButton = document.getElementById('deletePohonButton');

        if (!modal || !content) return;

        // Format date time for display
        const formatDateTime = (dateString) => {
            if (!dateString) return '-';
            const date = new Date(dateString);
            return date.toLocaleString('id-ID', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                timeZoneName: 'short'
            });
        };

        content.innerHTML = `
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg space-y-4">
                    <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M7.00016 1.99999C6.50307 1.99742 6.01319 2.11897 5.57495 2.35361C5.13671 2.58826 4.76397 2.92858 4.49052 3.34371C4.21707 3.75885 4.05157 4.23567 4.00902 4.73095C3.96646 5.22623 4.0482 5.7243 4.24683 6.17999C3.66683 6.74666 3.3335 7.51999 3.3335 8.33333C3.3335 9.99999 4.66683 11.3333 6.3335 11.3333C6.66683 11.3333 7.00016 11.26 7.3335 11.1467V14H8.66683V10.5133C9.00016 10.6067 9.3335 10.6667 9.66683 10.6667C10.1483 10.6667 10.6251 10.5718 11.07 10.3876C11.5149 10.2033 11.9191 9.9332 12.2596 9.59272C12.6 9.25224 12.8701 8.84803 13.0544 8.40317C13.2387 7.95831 13.3335 7.48151 13.3335 6.99999C13.3335 6.51848 13.2387 6.04168 13.0544 5.59682C12.8701 5.15196 12.6 4.74775 12.2596 4.40727C11.9191 4.06679 11.5149 3.7967 11.07 3.61244C10.6251 3.42817 10.1483 3.33333 9.66683 3.33333H9.4935C8.94016 2.50666 8.00016 1.99999 7.00016 1.99999ZM7.00016 3.33333C7.88016 3.33333 8.60683 4.01999 8.66683 4.89999C8.9735 4.74666 9.3335 4.66666 9.66683 4.66666C10.2857 4.66666 10.8792 4.91249 11.3167 5.35008C11.7543 5.78766 12.0002 6.38116 12.0002 6.99999C12.0002 7.61883 11.7543 8.21233 11.3167 8.64991C10.8792 9.0875 10.2857 9.33333 9.66683 9.33333C9.02683 9.33333 8.42016 9.07333 7.9735 8.60666C7.91015 8.99584 7.71035 9.34977 7.40985 9.60507C7.10936 9.86037 6.7278 10.0004 6.3335 9.99999C5.89147 9.99999 5.46755 9.8244 5.15498 9.51184C4.84242 9.19928 4.66683 8.77536 4.66683 8.33333C4.66683 7.41333 5.20016 7.02666 6.00016 6.52666C5.46683 5.83999 5.3335 5.43999 5.3335 4.99999C5.3335 4.55797 5.50909 4.13404 5.82165 3.82148C6.13421 3.50892 6.55814 3.33333 7.00016 3.33333Z" fill="currentColor"/>
                            </svg>
                        </div>
                        Informasi Data Pohon
                    </h3>
                    <div>
                        <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Jenis Pohon</label>
                        <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                            <div class="text-sm font-medium text-darkslategray">${data.jenis_pohon || '-'}</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Tahun</label>
                        <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                            <div class="text-sm font-medium text-darkslategray">${data.tahun || '-'}</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Jumlah</label>
                        <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                            <div class="text-sm font-medium text-darkslategray">${data.jumlah || 0}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg space-y-4">
                    <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg width="17" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.6665 14C7.13317 14 5.79717 13.4918 4.6585 12.4753C3.51984 11.4589 2.86695 10.1893 2.69984 8.66667H4.0665C4.22206 9.82222 4.73606 10.7778 5.6085 11.5333C6.48095 12.2889 7.50028 12.6667 8.6665 12.6667C9.9665 12.6667 11.0694 12.214 11.9752 11.3087C12.8809 10.4033 13.3336 9.30044 13.3332 8C13.3327 6.69956 12.8801 5.59689 11.9752 4.692C11.0703 3.78711 9.96739 3.33422 8.6665 3.33333C7.89984 3.33333 7.18317 3.51111 6.5165 3.86667C5.84984 4.22222 5.28873 4.71111 4.83317 5.33333H6.6665V6.66667H2.6665V2.66667H3.99984V4.23333C4.5665 3.52222 5.25828 2.97222 6.07517 2.58333C6.89206 2.19444 7.75584 2 8.6665 2C9.49984 2 10.2805 2.15844 11.0085 2.47533C11.7365 2.79222 12.3698 3.21978 12.9085 3.758C13.4472 4.29622 13.8749 4.92956 14.1918 5.658C14.5087 6.38644 14.6669 7.16711 14.6665 8C14.6661 8.83289 14.5078 9.61356 14.1918 10.342C13.8758 11.0704 13.4481 11.7038 12.9085 12.242C12.3689 12.7802 11.7356 13.208 11.0085 13.5253C10.2814 13.8427 9.50073 14.0009 8.6665 14ZM10.5332 10.8L7.99984 8.26667V4.66667H9.33317V7.73333L11.4665 9.86667L10.5332 10.8Z" fill="currentColor"/>
                            </svg>
                        </div>
                        Log
                    </h3>
                    <div class="flex flex-col items-start justify-start gap-4">
                        <div class="flex flex-row items-start space-x-4">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.99984 9.66667L12.0832 11.75C12.2359 11.9028 12.3123 12.0972 12.3123 12.3333C12.3123 12.5694 12.2359 12.7639 12.0832 12.9167C11.9304 13.0694 11.7359 13.1458 11.4998 13.1458C11.2637 13.1458 11.0693 13.0694 10.9165 12.9167L8.58317 10.5833C8.49984 10.5 8.43734 10.4064 8.39567 10.3025C8.354 10.1986 8.33317 10.0908 8.33317 9.97917V6.66666C8.33317 6.43055 8.41317 6.23278 8.57317 6.07333C8.73317 5.91389 8.93095 5.83389 9.1665 5.83333C9.40206 5.83278 9.60012 5.91278 9.76067 6.07333C9.92123 6.23389 10.0009 6.43166 9.99984 6.66666V9.66667ZM14.9998 5H13.3332C13.0971 5 12.8993 4.92028 12.7398 4.76083C12.5804 4.60139 12.5004 4.40333 12.4998 4.16666C12.4993 3.93 12.5793 3.73222 12.7398 3.57333C12.9004 3.41444 13.0982 3.33444 13.3332 3.33333H14.9998V1.66666C14.9998 1.43055 15.0798 1.23278 15.2398 1.07333C15.3998 0.913887 15.5976 0.833887 15.8332 0.833331C16.0687 0.832776 16.2668 0.912776 16.4273 1.07333C16.5879 1.23389 16.6676 1.43166 16.6665 1.66666V3.33333H18.3332C18.5693 3.33333 18.7673 3.41333 18.9273 3.57333C19.0873 3.73333 19.1671 3.93111 19.1665 4.16666C19.1659 4.40222 19.0859 4.60028 18.9265 4.76083C18.7671 4.92139 18.5693 5.00111 18.3332 5H16.6665V6.66666C16.6665 6.90278 16.5865 7.10083 16.4265 7.26083C16.2665 7.42083 16.0687 7.50055 15.8332 7.5C15.5976 7.49944 15.3998 7.41944 15.2398 7.26C15.0798 7.10055 14.9998 6.90278 14.9998 6.66666V5ZM9.1665 17.5C8.12484 17.5 7.14928 17.3056 6.23984 16.9167C5.33039 16.5278 4.53512 15.9931 3.85401 15.3125C3.17289 14.6319 2.63817 13.8367 2.24984 12.9267C1.86151 12.0167 1.66706 11.0411 1.66651 10C1.66595 8.95889 1.86039 7.98333 2.24984 7.07333C2.63928 6.16333 3.17401 5.36805 3.85401 4.6875C4.53401 4.00694 5.32928 3.47222 6.23984 3.08333C7.15039 2.69444 8.12595 2.5 9.1665 2.5C9.31928 2.5 9.46178 2.50361 9.594 2.51083C9.72623 2.51805 9.86845 2.53528 10.0207 2.5625C10.2568 2.5625 10.4548 2.6425 10.6148 2.8025C10.7748 2.9625 10.8546 3.16028 10.854 3.39583C10.8534 3.63139 10.7734 3.82944 10.614 3.99C10.4546 4.15055 10.2568 4.23028 10.0207 4.22916C9.86789 4.22916 9.72539 4.21861 9.59317 4.1975C9.46095 4.17639 9.31873 4.16611 9.1665 4.16666C7.52762 4.16666 6.14567 4.72916 5.02067 5.85416C3.89567 6.97916 3.33317 8.36111 3.33317 10C3.33317 11.6389 3.89567 13.0208 5.02067 14.1458C6.14567 15.2708 7.52762 15.8333 9.1665 15.8333C10.8054 15.8333 12.1873 15.2708 13.3123 14.1458C14.4373 13.0208 14.9998 11.6389 14.9998 10C14.9998 9.76389 15.0798 9.56611 15.2398 9.40667C15.3998 9.24722 15.5976 9.16722 15.8332 9.16667C16.0687 9.16611 16.2668 9.24611 16.4273 9.40667C16.5879 9.56722 16.6676 9.765 16.6665 10C16.6665 11.0417 16.4721 12.0175 16.0832 12.9275C15.6943 13.8375 15.1596 14.6325 14.479 15.3125C13.7984 15.9925 13.0032 16.5272 12.0932 16.9167C11.1832 17.3061 10.2076 17.5006 9.1665 17.5Z" fill="currentColor"/>
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <div class="text-sm font-semibold text-darkslategray mb-1">Dibuat pada</div>
                                <div class="text-sm text-slategray">${formatDateTime(data.created_at)}</div>
                            </div>
                        </div>
                        <div class="flex flex-row items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.021 10.3345C2.95608 12.7238 5.28066 14.4167 8.00016 14.4167C11.5439 14.4167 14.4168 11.5437 14.4168 8M13.9793 5.6655C13.0454 3.27558 10.7202 1.58333 8.00016 1.58333C4.45641 1.58333 1.5835 4.45625 1.5835 8M6.25016 10.3333H1.5835V15M14.4168 1V5.66667H9.75016" stroke="currentColor" stroke-width="2"/>
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <div class="text-sm font-semibold text-darkslategray mb-1">Terakhir diubah</div>
                                <div class="text-sm text-slategray">${formatDateTime(data.updated_at)}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Setup Edit button
        if (editButton) {
            if (data.edit_url) {
                editButton.onclick = () => {
                    window.location.href = data.edit_url;
                };
                editButton.classList.remove('hidden');
            } else {
                editButton.classList.add('hidden');
                editButton.onclick = null;
            }
        }

        // Setup Delete button
        if (deleteButton) {
            if (data.id) {
                deleteButton.onclick = () => {
                    window.closePohonModal();
                    window.dispatchEvent(
                        new CustomEvent('open-modal', { detail: `confirm-pohon-deletion-${data.id}-${data.tahun}` })
                    );
                };
                deleteButton.classList.remove('hidden');
            } else {
                deleteButton.classList.add('hidden');
                deleteButton.onclick = null;
            }
        }

        modal.classList.remove('hidden');
    };

    // Robust close modal (always get the modal element again)
    window.closePohonModal = () => {
        const modal = document.getElementById('pohonModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    };

    // Click outside modal (use currentTarget for robust backdrop checking)
    document.addEventListener('click', (e) => {
        const modal = document.getElementById('pohonModal');
        if (modal && !modal.classList.contains('hidden') && e.target === modal) {
            window.closePohonModal();
        }
    });

    // Escape key (always get modal and check visibility)
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const modal = document.getElementById('pohonModal');
            if (modal && !modal.classList.contains('hidden')) {
                window.closePohonModal();
            }
        }
    });
});