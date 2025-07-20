// Enhanced File Upload Handler with Fixed Drag Events
class FileUploadHandler {
    constructor() {
        this.dropzone = document.getElementById('dropzone');
        this.fileInput = document.getElementById('fileInput');
        this.imagePreview = document.getElementById('imagePreview');
        this.previewImage = document.getElementById('previewImage');
        this.dropzoneText = document.getElementById('dropzoneText');
        this.dropzoneContent = document.getElementById('dropzoneContent');
        
        // Modal elements
        this.imageModal = document.getElementById('imageModal');
        this.modalImage = document.getElementById('modalImage');
        
        // Counter untuk track drag events
        this.dragCounter = 0;
        
        this.init();
    }

    init() {
        this.initFileUpload();
        this.initModals();
    }

    initFileUpload() {
        if (!this.dropzone || !this.fileInput) return;

        // Event untuk file input
        this.fileInput.addEventListener('change', (e) => this.handleFileSelect(e));

        // Prevent default drag behaviors pada document dan body
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            document.body.addEventListener(eventName, this.preventDefaults, false);
            this.dropzone.addEventListener(eventName, this.preventDefaults, false);
        });

        // Drag events dengan counter untuk fix masalah child elements
        this.dropzone.addEventListener('dragenter', (e) => {
            this.dragCounter++;
            this.highlight();
        });

        this.dropzone.addEventListener('dragleave', (e) => {
            this.dragCounter--;
            // Hanya unhighlight jika counter = 0 (benar-benar keluar dari dropzone)
            if (this.dragCounter === 0) {
                this.unhighlight();
            }
        });

        this.dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            // Pastikan tetap highlighted saat dragover
            if (this.dragCounter > 0) {
                this.highlight();
            }
        });

        // Drop handler
        this.dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            this.dragCounter = 0; // Reset counter setelah drop
            this.unhighlight();
            this.handleDrop(e);
        });

        // Reset counter jika drag operation dibatalkan
        document.addEventListener('dragend', () => {
            this.dragCounter = 0;
            this.unhighlight();
        });
    }

    initModals() {
        // Setup image modal events
        if (this.imageModal) {
            this.imageModal.addEventListener('click', (e) => {
                if (e.target === this.imageModal) {
                    this.closeImageModal();
                }
            });
        }

        // Global keyboard events for ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                this.closeImageModal();
            }
        });
    }

    // File Upload Methods
    preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    highlight() {
        if (this.dropzone) {
            this.dropzone.classList.add('border-blue-400', 'bg-blue-50');
        }
        if (this.dropzoneText) {
            this.dropzoneText.textContent = 'Release to upload';
        }
    }

    unhighlight() {
        if (this.dropzone) {
            this.dropzone.classList.remove('border-blue-400', 'bg-blue-50');
        }
        if (this.dropzoneText) {
            this.dropzoneText.textContent = 'Drop File Here';
        }
    }

    handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length > 0) {
            this.setFileToInput(files[0]);
            this.handleFiles(files);
        }
    }

    handleFileSelect(e) {
        const files = e.target.files;
        this.handleFiles(files);
    }

    setFileToInput(file) {
        try {
            const dt = new DataTransfer();
            dt.items.add(file);
            this.fileInput.files = dt.files;
        } catch (error) {
            console.error('Error setting file to input:', error);
        }
    }

    handleFiles(files) {
        if (files.length > 0) {
            const file = files[0];

            // Validation for file type
            if (!file.type.startsWith('image/')) {
                alert('Please select an image file (PNG, JPG, WebP, SVG)');
                this.removeImage();
                return;
            }

            // Validation for file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size must be less than 5MB');
                this.removeImage();
                return;
            }

            this.previewFile(file);
        }
    }

    previewFile(file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            if (this.previewImage) {
                this.previewImage.src = e.target.result;
                this.showPreview();
                
                // Update modal image source for new uploads
                if (this.modalImage) {
                    this.modalImage.src = e.target.result;
                }
            }
        };
        reader.onerror = (e) => {
            console.error('Error reading file:', e);
            alert('Error reading file');
        };
        reader.readAsDataURL(file);
    }

    showPreview() {
        // Hide existing image preview if exists
        const existingPreview = document.getElementById('existingImagePreview');
        if (existingPreview) {
            existingPreview.style.display = 'none';
        }

        // Hide initial content
        if (this.dropzoneContent) {
            this.dropzoneContent.style.display = 'none';
        }

        // Show preview
        if (this.imagePreview) {
            this.imagePreview.classList.remove('hidden');
            this.imagePreview.style.display = 'flex';
        }
    }

    removeImage() {
        // Reset drag counter
        this.dragCounter = 0;
        this.unhighlight();

        // Clear file input
        if (this.fileInput) {
            this.fileInput.value = '';
        }

        // Hide preview
        if (this.imagePreview) {
            this.imagePreview.classList.add('hidden');
            this.imagePreview.style.display = 'none';
        }

        // Show initial content
        if (this.dropzoneContent) {
            this.dropzoneContent.style.display = 'flex';
            this.dropzoneContent.classList.remove('hidden');
        }

        // Show existing image preview if exists (for edit mode)
        const existingPreview = document.getElementById('existingImagePreview');
        if (existingPreview) {
            existingPreview.style.display = 'flex';
        }

        // Clear preview image
        if (this.previewImage) {
            this.previewImage.src = '';
        }

        // Reset modal image to existing image if in edit mode
        if (this.modalImage) {
            const existingImg = document.querySelector('#existingImagePreview img');
            if (existingImg) {
                this.modalImage.src = existingImg.src;
            } else {
                this.modalImage.src = '';
            }
        }
    }

    // Modal Management Methods
    openImageModal() {
        if (this.imageModal) {
            // Update modal image source based on current visible image
            if (this.modalImage) {
                const currentImage = this.imagePreview && !this.imagePreview.classList.contains('hidden') 
                    ? this.previewImage.src 
                    : document.querySelector('#existingImagePreview img')?.src || '';
                
                this.modalImage.src = currentImage;
            }
            
            this.imageModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }

    closeImageModal() {
        if (this.imageModal) {
            this.imageModal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }
}

// Global functions for inline event handlers
function browseFile() {
    const fileInput = document.getElementById('fileInput');
    if (fileInput) {
        fileInput.click();
    }
}

function removeImage() {
    if (window.fileUploadHandler) {
        window.fileUploadHandler.removeImage();
    }
}

function openImageModal() {
    if (window.fileUploadHandler) {
        window.fileUploadHandler.openImageModal();
    }
}

function closeImageModal() {
    if (window.fileUploadHandler) {
        window.fileUploadHandler.closeImageModal();
    }
}

// Initialize the file upload handler when the document is ready
document.addEventListener('DOMContentLoaded', () => {
    window.fileUploadHandler = new FileUploadHandler();
    window.browseFile = browseFile;
    window.removeImage = removeImage;
    window.openImageModal = openImageModal;
    window.closeImageModal = closeImageModal;
});