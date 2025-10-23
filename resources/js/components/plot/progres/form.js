export function FormProgresData(config = {}) {
    return {
        // Configuration and initial data
        action: config.action || '',
        errors: config.errors || {},
        existingFiles: config.existingFiles || {},
        baseFields: config.baseFields || {},
        data: config.data || {},
        
        // Form state
        newFiles: {},
        removedFiles: {},
        modalState: {
            isOpen: false,
            imageUrl: '',
            fileName: '',
            fileType: 'image'
        },

        // Initialize component
        init() {
            this.setupEventListeners();
            this.createModalContainer();
        },

        setupEventListeners() {
            // Setup any additional event listeners if needed
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && this.modalState.isOpen) {
                    this.closeModal();
                }
            });
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

        // Error handling
        getErrorMessage(fieldKey) {
            return this.errors && this.errors[fieldKey] ? this.errors[fieldKey][0] : '';
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

        // Field management
        get currentFields() {
            if (!this.kategori || !this.aktivitas) return {};
            return this.categories[this.kategori]?.activities[this.aktivitas]?.fields || {};
        },

        baseDateFields() {
            const fields = this.baseFields;
            return Object.fromEntries(Object.entries(fields).filter(([key, field]) => field.type === 'date'));
        },

        baseNonDateFields() {
            const fields = this.baseFields;
            return Object.fromEntries(Object.entries(fields).filter(([key, field]) => field.type !== 'date'));
        },

        // Form validation
        validateForm() {
            // Add any client-side validation logic here
            return true;
        },

        // Form submission
        onSubmit() {
            if (!this.validateForm()) {
                return false;
            }
            // Form will submit naturally
            return true;
        }
    };
}

// Global helper function for backward compatibility
window.FormProgresData = FormProgresData;