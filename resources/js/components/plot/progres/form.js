export function FormProgresData(config = {}) {
    return {
        // Configuration and initial data
        action: config.action || '',
        errors: config.errors || {},
        existingFiles: config.existingFiles || {},
        baseFields: config.baseFields || {},
        formData: config.data || {},
        masterPohon: config.masterPohon || {},
        
        // Form state
        newFiles: {},
        removedFiles: {},
        modalState: {
            isOpen: false,
            imageUrl: '',
            fileName: '',
            fileType: 'image'
        },

        // Monitoring survival rate state
        monitoringHint: {
            totalPlanted: null,
            isLoading: false,
            fetchedForJenisPohonId: null, 
        },

        // Field management
        get currentFields() {
            if (!this.kategori || !this.aktivitas) return {};
            return this.categories[this.kategori]?.activities[this.aktivitas]?.fields || {};
        },

        get baseDateFields() {
            const fields = this.baseFields;
            return Object.fromEntries(
                Object.entries(fields).filter(([key, field]) => field.type === 'date')
            );
        },

        get baseNonDateFields() {
            const fields = this.baseFields;
            return Object.fromEntries(
                Object.entries(fields).filter(([key, field]) => field.type !== 'date')
            );
        },

        // Initialize component
        init() {
            console.log('FormProgresData initialized');
            
            this.setupEventListeners();
            this.createModalContainer();

            const allFields = { 
                ...this.baseDateFields, 
                ...this.baseNonDateFields 
            };
            
            for (const key in allFields) {
                if (this.formData[key] === undefined) {
                    this.formData[key] = '';
                }
            }

            this.setupMonitoringWatchers();
        },

        setupEventListeners() {
            // Setup any additional event listeners if needed
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.modalState.isOpen) {
                    this.closeModal();
                }
            });
        },

        // Monitoring watchers
        setupMonitoringWatchers() {
            if (typeof this.$watch !== 'function') {
                console.warn('Alpine.js $watch not available');
                return;
            }

            // Watch ONLY jenis_pohon_id changes (fetch hint)
            this.$watch('formData.jenis_pohon_id', (value, oldValue) => {
                if (!this.isMonitoringActivity()) return;

                // Fetch hint if changed
                if (value && value !== this.monitoringHint.fetchedForJenisPohonId) {
                    console.log('Fetching planted trees hint for:', value);
                    this.fetchPlantedTreesHint(value);
                } else if (!value) {
                    // Clear hint if jenis_pohon cleared
                    this.clearMonitoringHint();
                }
            });

            // ✅ NO watchers for hidup/mati (backend will validate on submit)
        },

        // Create modal container in DOM
        createModalContainer() {
            if (!document.getElementById('file-preview-modal')) {
                const modalContainer = document.createElement('div');
                modalContainer.id = 'file-preview-modal';
                modalContainer.style.display = 'none';
                document.body.appendChild(modalContainer);
            }
        },

        // Helper to get tree options based on filter
        getTreeOptions(filterKategori) {
            // No filter, return all trees
            if (!filterKategori) {
                return Object.values(this.masterPohon).flat()
                    .sort((a, b) => a.nama_pohon.localeCompare(b.nama_pohon));
            }

            // Handle Array of Strings
            if (Array.isArray(filterKategori)) {
                return filterKategori
                    .flatMap(kategori => this.masterPohon[kategori] || [])
                    .sort((a, b) => a.nama_pohon.localeCompare(b.nama_pohon));
            }

            // Handle Single String
            return (this.masterPohon[filterKategori] || [])
                .sort((a, b) => a.nama_pohon.localeCompare(b.nama_pohon));
        },

        // Error handling
        getErrorMessage(fieldKey) {
            if (this.errors && this.errors[fieldKey]) {
                return Array.isArray(this.errors[fieldKey]) 
                    ? this.errors[fieldKey][0] 
                    : this.errors[fieldKey];
            }
            
            if (this.errors) {
                const nestedErrors = Object.keys(this.errors)
                    .filter(errorKey => errorKey.startsWith(fieldKey + '.'))
                    .map(errorKey => this.errors[errorKey]);
                
                if (nestedErrors.length > 0) {
                    return Array.isArray(nestedErrors[0]) 
                        ? nestedErrors[0][0] 
                        : nestedErrors[0];
                }
            }
            
            return '';
        },

        // File handling methods
        getExistingFiles(key) {
            const files = this.existingFiles[key];
            if (!files) return [];
            
            // Handle both single file (string) and multiple files (array)
            const fileArray = Array.isArray(files) ? files : [files];
            const removedFiles = this.getRemovedFiles(key);
            
            // Filter out removed files
            return fileArray.filter((file, index) => !removedFiles.includes(index));
        },

        hasExistingFiles(key) {
            return this.getExistingFiles(key).length > 0;
        },

        getRemovedFiles(key) {
            return this.removedFiles[key] || [];
        },

        getFileName(filePath) {
            if (!filePath) return 'Unknown file';
            if (typeof filePath === 'object' && filePath.name) return filePath.name;
            return filePath.split('/').pop() || filePath;
        },

        getFileUrl(filePath) {
            if (!filePath) return '';
            if (typeof filePath === 'object' && filePath.url) return filePath.url;
            return filePath.startsWith('http') ? filePath : `/storage/${filePath}`;
        },

        isImageFile(fileName) {
            if (!fileName) return false;
            const ext = fileName.split('.').pop()?.toLowerCase();
            return ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(ext);
        },

        handleFileChange(event, key) {
            const files = event.target.files;
            this.newFiles[key] = files;
            
            // Update display text
            const displayRef = 'fileDisplay_' + key;
            const displayElement = this.$refs[displayRef];
            if (displayElement) {
                displayElement.textContent = this.getFileDisplayText(key);
            }
        },

        removeExistingFile(key, index) {
            if (!this.removedFiles[key]) {
                this.removedFiles[key] = [];
            }
            
            // Add index to removed files list
            if (!this.removedFiles[key].includes(index)) {
                this.removedFiles[key].push(index);
            }
        },

        restoreExistingFile(key, index) {
            if (this.removedFiles[key]) {
                const indexPos = this.removedFiles[key].indexOf(index);
                if (indexPos > -1) {
                    this.removedFiles[key].splice(indexPos, 1);
                }
            }
        },

        isFileMarkedForRemoval(key, index) {
            return this.getRemovedFiles(key).includes(index);
        },

        getFileDisplayText(key) {
            const newFiles = this.newFiles[key];
            const hasExisting = this.hasExistingFiles(key);
            
            if (newFiles && newFiles.length > 0) {
                return newFiles.length === 1 ? newFiles[0].name : `${newFiles.length} files selected`;
            }
            
            if (hasExisting) {
                const existingCount = this.getExistingFiles(key).length;
                return `Keep existing (${existingCount} file${existingCount > 1 ? 's' : ''})`;
            }
            
            return 'No file chosen';
        },

        // Modal functionality
        openFileModal(file, fileName = '') {
            const fileUrl = this.getFileUrl(file.url || file);
            const name = fileName || this.getFileName(file.name || file.url || file);
            
            this.modalState = {
                isOpen: true,
                imageUrl: fileUrl,
                fileName: name,
                fileType: this.isImageFile(name) ? 'image' : 'other'
            };
            
            this.renderModal();
            
            // Prevent body scroll when modal is open
            document.body.style.overflow = 'hidden';
        },

        closeModal() {
            this.modalState.isOpen = false;
            document.body.style.overflow = '';
            
            // Remove modal from DOM
            const modalContainer = document.getElementById('file-preview-modal');
            if (modalContainer) {
                modalContainer.style.display = 'none';
                modalContainer.innerHTML = '';
            }
        },

        renderModal() {
            const modalContainer = document.getElementById('file-preview-modal');
            if (!modalContainer) return;

            const modalHTML = this.generateModalHTML();
            modalContainer.innerHTML = modalHTML;
            modalContainer.style.display = 'block';

            // Add event listeners
            this.attachModalEventListeners();
        },

        generateModalHTML() {
            const { imageUrl, fileName, fileType } = this.modalState;
            
            return `
                <div class="fixed inset-0 z-50 bg-black/75 flex items-center justify-center p-4 modal-overlay">
                    <div class="relative max-w-4xl max-h-full p-4">
                        <button class="close-btn absolute top-6 right-6 rounded-lg border-none text-darkslategray-200 bg-white hover:text-white hover:bg-gray-600 transition-colors p-2">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        ${this.generateModalContent(fileType, imageUrl, fileName)}
                    </div>
                </div>
            `;
        },

        generateModalContent(fileType, imageUrl, fileName) {
            switch (fileType) {
                case 'image':
                    return `
                        <img src="${imageUrl}" 
                            alt="${this.escapeHtml(fileName)}"
                            class="max-w-full max-h-[80vh] object-contain rounded-lg bg-white shadow-lg" />
                    `;
                
                default:
                    return `
                        <div class="bg-white rounded-lg shadow-lg p-8 text-center max-w-md mx-auto">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h4 class="text-lg font-medium text-gray-900 mb-2">File Preview</h4>
                            <p class="text-gray-600 mb-4">${this.escapeHtml(fileName)}</p>
                            <p class="text-sm text-gray-500">This file type cannot be previewed in the browser.</p>
                        </div>
                    `;
            }
        },

        attachModalEventListeners() {
            const modalContainer = document.getElementById('file-preview-modal');
            if (!modalContainer) return;

            // Close on overlay click
            const overlay = modalContainer.querySelector('.modal-overlay');
            if (overlay) {
                overlay.addEventListener('click', (e) => {
                    if (e.target === overlay) {
                        this.closeModal();
                    }
                });
            }

            // Close button
            const closeBtn = modalContainer.querySelector('.close-btn');
            if (closeBtn) {
                closeBtn.addEventListener('click', () => this.closeModal());
            }
        },

        escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        },

        // Form validation
        validateForm() {
            // Add any client-side validation logic here
            return true;
        },

        /**
         * Check if current activity is monitoring
         */
        isMonitoringActivity() {
            const aktivitas = this.aktivitas;
            const monitoringActivities = [
                'monitoring_survival_rate',
                'monitoring_pertumbuhan'
            ];
            return monitoringActivities.includes(aktivitas);
        },

        /**
         * Fetch planted trees hint (called ONCE per jenis_pohon selection)
         */
        async fetchPlantedTreesHint(jenisPohonId) {
            if (!jenisPohonId || !this.isMonitoringActivity()) {
                return;
            }

            // Skip if already fetched for this ID
            if (jenisPohonId === this.monitoringHint.fetchedForJenisPohonId) {
                console.log('Already fetched hint for jenis_pohon_id:', jenisPohonId);
                return;
            }

            this.monitoringHint.isLoading = true;

            try {
                const plotId = this.getPlotIdFromUrl();
                
                if (!plotId) {
                    throw new Error('Plot ID not found in URL');
                }
                
                const url = `/plot/${plotId}/progres/planted-trees?jenis_pohon_id=${jenisPohonId}`;
                
                console.log('Fetching hint from:', url);
                
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                
                if (data.success) {
                    // Cache the result
                    this.monitoringHint.totalPlanted = data.total;
                    this.monitoringHint.fetchedForJenisPohonId = jenisPohonId;
                    
                    console.log(`Hint loaded: ${data.total} trees planted`);
                } else {
                    console.warn('Failed to fetch hint:', data.message);
                    this.monitoringHint.totalPlanted = null;
                }

            } catch (error) {
                console.error('Error fetching planted trees hint:', error);
                this.monitoringHint.totalPlanted = null;
            } finally {
                this.monitoringHint.isLoading = false;
            }
        },

        /**
         * Clear monitoring hint
         */
        clearMonitoringHint() {
            this.monitoringHint.totalPlanted = null;
            this.monitoringHint.fetchedForJenisPohonId = null;
            console.log('Monitoring hint cleared');
        },

        /**
         * Get plot ID from URL
         */
        getPlotIdFromUrl() {
            const match = window.location.pathname.match(/\/plot\/(\d+)/);
            return match ? match[1] : null;
        },

        onSubmit(event) {
            if (!this.validateForm()) {
                if (event) event.preventDefault();
                return false;
            }
            return true;
        },
    };
}

// Global helper function for backward compatibility
window.FormProgresData = FormProgresData;