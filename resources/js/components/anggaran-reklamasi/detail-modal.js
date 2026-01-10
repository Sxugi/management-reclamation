document.addEventListener("turbo:load", () => {
    window.openAnggaranModal = (items, meta = {}) => {
        const modal = document.getElementById('anggaranModal');
        const content = document.getElementById('anggaranModalContent');

        if (!modal || !content) return;

        // Helper: format date
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

        // Compute total
        const total = items.reduce((sum, item) => sum + Number(item.nominal || 0), 0);

        // Get oldest created_at and latest updated_at
        const created_at = items.length
            ? items.reduce((oldest, item) => (!oldest || new Date(item.created_at) < new Date(oldest) ? item.created_at : oldest), null)
            : null;
        const updated_at = items.length
            ? items.reduce((latest, item) => (!latest || new Date(item.updated_at) > new Date(latest) ? item.updated_at : latest), null)
            : null;

        const monthNames = [
            "Januari", "Februari", "Maret", "April", "Mei", "Juni",
            "Juli", "Agustus", "September", "Oktober", "November", "Desember"
        ];

        // General info (from meta or first item)
        const tahun = meta.tahun || (items[0]?.tahun ?? '-');
        const bulan = meta.bulan || (monthNames[items[0]?.bulan - 1] ?? '-');
        const quarter = meta.quarter || (items[0]?.quarter ?? '-');

        content.innerHTML = `
            <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg space-y-4 mb-6">
                <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <svg width="16" height="16" viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M8.67958 2C7.17391 2 5.80225 2.49733 4.99058 2.90367C4.91725 2.94033 4.8488 2.97611 4.78525 3.011C4.65925 3.07967 4.55191 3.14367 4.46658 3.2L5.38991 4.55933L5.82458 4.73233C7.52325 5.58933 9.80125 5.58933 11.5002 4.73233L11.9936 4.47633L12.8666 3.2C12.6855 3.08237 12.4975 2.97584 12.3036 2.881C11.4959 2.479 10.1569 2 8.67991 2M6.53258 3.53867C6.20569 3.47715 5.88278 3.39612 5.56558 3.296C6.32591 2.95833 7.45891 2.6 8.67991 2.6C9.52558 2.6 10.3252 2.772 10.9866 2.99C10.2116 3.099 9.38458 3.284 8.59658 3.51167C7.97658 3.691 7.25191 3.67167 6.53258 3.53867ZM11.8526 5.22667L11.7706 5.268C9.90191 6.21067 7.42325 6.21067 5.55458 5.268L5.47691 5.22867C2.66925 8.309 0.525913 13.999 8.67958 13.999C16.8332 13.999 14.6379 8.20333 11.8526 5.22667ZM8.33325 8C8.15644 8 7.98687 8.07024 7.86184 8.19526C7.73682 8.32029 7.66658 8.48986 7.66658 8.66667C7.66658 8.84348 7.73682 9.01305 7.86184 9.13807C7.98687 9.2631 8.15644 9.33333 8.33325 9.33333V8ZM8.99991 7.33333V7H8.33325V7.33333C7.97962 7.33333 7.64049 7.47381 7.39044 7.72386C7.14039 7.97391 6.99991 8.31304 6.99991 8.66667C6.99991 9.02029 7.14039 9.35943 7.39044 9.60948C7.64049 9.85952 7.97962 10 8.33325 10V11.3333C8.04325 11.3333 7.79625 11.1483 7.70425 10.889C7.6906 10.8466 7.66858 10.8073 7.63949 10.7735C7.6104 10.7398 7.57484 10.7122 7.5349 10.6924C7.49496 10.6726 7.45146 10.661 7.40697 10.6584C7.36248 10.6557 7.31791 10.6619 7.27588 10.6768C7.23386 10.6916 7.19524 10.7148 7.16231 10.7448C7.12938 10.7748 7.10281 10.8112 7.08416 10.8516C7.06552 10.8921 7.05518 10.9359 7.05376 10.9805C7.05235 11.025 7.05988 11.0694 7.07591 11.111C7.16782 11.371 7.33809 11.5961 7.56327 11.7554C7.78845 11.9146 8.05746 12 8.33325 12V12.3333H8.99991V12C9.35354 12 9.69267 11.8595 9.94272 11.6095C10.1928 11.3594 10.3332 11.0203 10.3332 10.6667C10.3332 10.313 10.1928 9.97391 9.94272 9.72386C9.69267 9.47381 9.35354 9.33333 8.99991 9.33333V8C9.28991 8 9.53691 8.185 9.62891 8.44433C9.64256 8.48676 9.66458 8.52602 9.69367 8.55979C9.72276 8.59356 9.75832 8.62115 9.79826 8.64094C9.8382 8.66072 9.8817 8.6723 9.92619 8.67498C9.97068 8.67766 10.0153 8.67139 10.0573 8.65654C10.0993 8.64169 10.1379 8.61857 10.1708 8.58854C10.2038 8.5585 10.2304 8.52217 10.249 8.48169C10.2676 8.4412 10.278 8.39739 10.2794 8.35285C10.2808 8.3083 10.2733 8.26392 10.2572 8.22233C10.1653 7.96231 9.99507 7.73719 9.76989 7.57798C9.54471 7.41876 9.2757 7.33329 8.99991 7.33333ZM8.99991 10V11.3333C9.17672 11.3333 9.34629 11.2631 9.47132 11.1381C9.59634 11.013 9.66658 10.8435 9.66658 10.6667C9.66658 10.4899 9.59634 10.3203 9.47132 10.1953C9.34629 10.0702 9.17672 10 8.99991 10Z" fill="currentColor"/>
                        </svg>
                    </div>
                    Informasi Anggaran Reklamasi
                </h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Tahun</label>
                        <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                            <div class="text-sm font-medium text-darkslategray">${tahun}</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Bulan</label>
                        <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                            <div class="text-sm font-medium text-darkslategray">${bulan}</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Quarter</label>
                        <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                            <div class="text-sm font-medium text-darkslategray">${quarter}</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-slategray uppercase tracking-wide mb-2">Total Nominal</label>
                        <div class="bg-gray-50 border border-gainsboro rounded-lg p-2">
                            <div class="text-sm font-medium text-darkslategray">Rp.${total.toLocaleString('id-ID')}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white border border-gainsboro rounded-xl p-6 pt-3 shadow-lg space-y-4">
                <h3 class="text-sm font-semibold text-darkslategray mt-2 mb-2">Rincian Anggaran</h3>
                ${items.map(item => {
                    const editDisabledAttr = !item.can_update ? 'disabled' : '';
                    const editAction = item.can_update 
                        ? `window.location.href='${item.edit_url}'`
                        : `return false;`;

                    const deleteDisabledAttr = !item.can_delete ? 'disabled' : '';
                    const deleteDataAttr = item.can_delete 
                        ? `data-id="${item.anggaran_reklamasi_id}"` 
                        : '';

                    return `
                    <li class="flex flex-row items-center justify-between bg-gray-50 border border-gainsboro rounded-lg px-3 py-2">
                        <div>
                            <div class="font-medium text-darkslategray">${item.kategori_anggaran}</div>
                            <div class="text-xs text-slategray">Rp.${Number(item.nominal).toLocaleString('id-ID')}</div>
                        </div>
                        <div class="flex flex-row items-center justify-center gap-2">
                            <button onclick="${editAction}" ${editDisabledAttr} class="edit-anggaran-btn cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M5.83301 5.83334H4.99967C4.55765 5.83334 4.13372 6.00894 3.82116 6.3215C3.5086 6.63406 3.33301 7.05798 3.33301 7.50001V15C3.33301 15.442 3.5086 15.866 3.82116 16.1785C4.13372 16.4911 4.55765 16.6667 4.99967 16.6667H12.4997C12.9417 16.6667 13.3656 16.4911 13.6782 16.1785C13.9907 15.866 14.1663 15.442 14.1663 15V14.1667" stroke="#27374D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M13.3333 4.16666L15.8333 6.66666M16.9875 5.4875C17.3157 5.15929 17.5001 4.71415 17.5001 4.25C17.5001 3.78585 17.3157 3.3407 16.9875 3.0125C16.6593 2.68429 16.2142 2.49991 15.75 2.49991C15.2858 2.49991 14.8407 2.68429 14.5125 3.0125L7.5 10V12.5H10L16.9875 5.4875Z" stroke="#27374D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <button ${deleteDataAttr} ${deleteDisabledAttr} class="delete-anggaran-btn cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg width="13" height="16" viewBox="0 0 13 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M3.92321 1.01855L3.71429 1.4375H0.928571C0.414955 1.4375 0 1.85645 0 2.375C0 2.89355 0.414955 3.3125 0.928571 3.3125H12.0714C12.585 3.3125 13 2.89355 13 2.375C13 1.85645 12.585 1.4375 12.0714 1.4375H9.28571L9.07679 1.01855C8.92009 0.699219 8.59799 0.5 8.24688 0.5H4.75312C4.40201 0.5 4.07991 0.699219 3.92321 1.01855ZM12.0714 4.25H0.928571L1.54375 14.1816C1.59018 14.9229 2.19955 15.5 2.93371 15.5H10.0663C10.8004 15.5 11.4098 14.9229 11.4562 14.1816L12.0714 4.25Z" fill="#F24822"/>
                                </svg>
                            </button>
                        </div>
                    </li>
                `}).join('')}
            </div>
            <div class="mt-6 text-sm text-slategray space-y-2">
                <div class="bg-white border border-gainsboro rounded-xl p-6 shadow-lg space-y-4">
                    <h3 class="text-lg font-semibold text-darkslategray mb-4 flex items-center gap-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                            <svg width="17" height="16" viewBox="0 0 17 16" fill="none"><path d="M8.6665 14C7.13317 14 5.79717 13.4918 4.6585 12.4753C3.51984 11.4589 2.86695 10.1893 2.69984 8.66667H4.0665C4.22206 9.82222 4.73606 10.7778 5.6085 11.5333C6.48095 12.2889 7.50028 12.6667 8.6665 12.6667C9.9665 12.6667 11.0694 12.214 11.9752 11.3087C12.8809 10.4033 13.3336 9.30044 13.3332 8C13.3327 6.69956 12.8801 5.59689 11.9752 4.692C11.0703 3.78711 9.96739 3.33422 8.6665 3.33333C7.89984 3.33333 7.18317 3.51111 6.5165 3.86667C5.84984 4.22222 5.28873 4.71111 4.83317 5.33333H6.6665V6.66667H2.6665V2.66667H3.99984V4.23333C4.5665 3.52222 5.25828 2.97222 6.07517 2.58333C6.89206 2.19444 7.75584 2 8.6665 2C9.49984 2 10.2805 2.15844 11.0085 2.47533C11.7365 2.79222 12.3698 3.21978 12.9085 3.758C13.4472 4.29622 13.8749 4.92956 14.1918 5.658C14.5087 6.38644 14.6669 7.16711 14.6665 8C14.6661 8.83289 14.5078 9.61356 14.1918 10.342C13.8758 11.0704 13.4481 11.7038 12.9085 12.242C12.3689 12.7802 11.7356 13.208 11.0085 13.5253C10.2814 13.8427 9.50073 14.0009 8.6665 14ZM10.5332 10.8L7.99984 8.26667V4.66667H9.33317V7.73333L11.4665 9.86667L10.5332 10.8Z" fill="currentColor"/></svg>
                        </div>
                        Log
                    </h3>
                    <div class="flex flex-col items-start justify-start gap-4">
                        <div class="flex flex-row items-start space-x-4">
                            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"><path d="M9.99984 9.66667L12.0832 11.75C12.2359 11.9028 12.3123 12.0972 12.3123 12.3333C12.3123 12.5694 12.2359 12.7639 12.0832 12.9167C11.9304 13.0694 11.7359 13.1458 11.4998 13.1458C11.2637 13.1458 11.0693 13.0694 10.9165 12.9167L8.58317 10.5833C8.49984 10.5 8.43734 10.4064 8.39567 10.3025C8.354 10.1986 8.33317 10.0908 8.33317 9.97917V6.66666C8.33317 6.43055 8.41317 6.23278 8.57317 6.07333C8.73317 5.91389 8.93095 5.83389 9.1665 5.83333C9.40206 5.83278 9.60012 5.91278 9.76067 6.07333C9.92123 6.23389 10.0009 6.43166 9.99984 6.66666V9.66667ZM14.9998 5H13.3332C13.0971 5 12.8993 4.92028 12.7398 4.76083C12.5804 4.60139 12.5004 4.40333 12.4998 4.16666C12.4993 3.93 12.5793 3.73222 12.7398 3.57333C12.9004 3.41444 13.0982 3.33444 13.3332 3.33333H14.9998V1.66666C14.9998 1.43055 15.0798 1.23278 15.2398 1.07333C15.3998 0.913887 15.5976 0.833887 15.8332 0.833331C16.0687 0.832776 16.2668 0.912776 16.4273 1.07333C16.5879 1.23389 16.6676 1.43166 16.6665 1.66666V3.33333H18.3332C18.5693 3.33333 18.7673 3.41333 18.9273 3.57333C19.0873 3.73333 19.1671 3.93111 19.1665 4.16666C19.1659 4.40222 19.0859 4.60028 18.9265 4.76083C18.7671 4.92139 18.5693 5.00111 18.3332 5H16.6665V6.66666C16.6665 6.90278 16.5865 7.10083 16.4265 7.26083C16.2665 7.42083 16.0687 7.50055 15.8332 7.5C15.5976 7.49944 15.3998 7.41944 15.2398 7.26C15.0798 7.10055 14.9998 6.90278 14.9998 6.66666V5ZM9.1665 17.5C8.12484 17.5 7.14928 17.3056 6.23984 16.9167C5.33039 16.5278 4.53512 15.9931 3.85401 15.3125C3.17289 14.6319 2.63817 13.8367 2.24984 12.9267C1.86151 12.0167 1.66706 11.0411 1.66651 10C1.66595 8.95889 1.86039 7.98333 2.24984 7.07333C2.63928 6.16333 3.17401 5.36805 3.85401 4.6875C4.53401 4.00694 5.32928 3.47222 6.23984 3.08333C7.15039 2.69444 8.12595 2.5 9.1665 2.5C9.31928 2.5 9.46178 2.50361 9.594 2.51083C9.72623 2.51805 9.86845 2.53528 10.0207 2.5625C10.2568 2.5625 10.4548 2.6425 10.6148 2.8025C10.7748 2.9625 10.8546 3.16028 10.854 3.39583C10.8534 3.63139 10.7734 3.82944 10.614 3.99C10.4546 4.15055 10.2568 4.23028 10.0207 4.22916C9.86789 4.22916 9.72539 4.21861 9.59317 4.1975C9.46095 4.17639 9.31873 4.16611 9.1665 4.16666C7.52762 4.16666 6.14567 4.72916 5.02067 5.85416C3.89567 6.97916 3.33317 8.36111 3.33317 10C3.33317 11.6389 3.89567 13.0208 5.02067 14.1458C6.14567 15.2708 7.52762 15.8333 9.1665 15.8333C10.8054 15.8333 12.1873 15.2708 13.3123 14.1458C14.4373 13.0208 14.9998 11.6389 14.9998 10C14.9998 9.76389 15.0798 9.56611 15.2398 9.40667C15.3998 9.24722 15.5976 9.16722 15.8332 9.16667C16.0687 9.16611 16.2668 9.24611 16.4273 9.40667C16.5879 9.56722 16.6676 9.765 16.6665 10C16.6665 11.0417 16.4721 12.0175 16.0832 12.9275C15.6943 13.8375 15.1596 14.6325 14.479 15.3125C13.7984 15.9925 13.0032 16.5272 12.0932 16.9167C11.1832 17.3061 10.2076 17.5006 9.1665 17.5Z" fill="currentColor"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <div class="text-sm font-semibold text-darkslategray mb-1">Dibuat pada</div>
                                <div class="text-sm text-slategray">${formatDateTime(created_at)}</div>
                            </div>
                        </div>
                        <div class="flex flex-row items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M2.021 10.3345C2.95608 12.7238 5.28066 14.4167 8.00016 14.4167C11.5439 14.4167 14.4168 11.5437 14.4168 8M13.9793 5.6655C13.0454 3.27558 10.7202 1.58333 8.00016 1.58333C4.45641 1.58333 1.5835 4.45625 1.5835 8M6.25016 10.3333H1.5835V15M14.4168 1V5.66667H9.75016" stroke="currentColor" stroke-width="2"/></svg>
                            </div>
                            <div class="flex flex-col">
                                <div class="text-sm font-semibold text-darkslategray mb-1">Terakhir diubah</div>
                                <div class="text-sm text-slategray">${formatDateTime(updated_at)}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Setup Delete button
        content.querySelectorAll('.delete-anggaran-btn').forEach(btn => {
            const id = btn.getAttribute('data-id');

            if (id) {
                btn.disabled = false;
                btn.onclick = function(e) {
                    e.preventDefault();
                    window.closeAnggaranModal();
                    window.dispatchEvent(
                        new CustomEvent('open-modal', { detail: `confirm-anggaran-deletion-${id}` })
                    );
                };
            } else {
                btn.disabled = true;
                btn.onclick = (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                };
            }
        });

        modal.classList.remove('hidden');
    };

    window.closeAnggaranModal = () => {
        const modal = document.getElementById('anggaranModal');
                if (modal) {
            modal.classList.add('hidden');
        }
    };

    document.addEventListener('click', (e) => {
        const modal = document.getElementById('anggaranModal');
        if (modal && !modal.classList.contains('hidden') && e.target === modal) {
            window.closeAnggaranModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const modal = document.getElementById('anggaranModal');
            if (modal && !modal.classList.contains('hidden')) {
                window.closeAnggaranModal();
            }
        }
    });
});