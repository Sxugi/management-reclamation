class DateFilter {
    constructor() {
        this.datePickerOpen = false;
        this.init();
    }

    init() {
        // Bind events
        document.addEventListener('DOMContentLoaded', () => {
            this.bindEvents();
        });
    }

    bindEvents() {
        // Date input change events
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');
        
        if (startDateInput) {
            startDateInput.addEventListener('change', () => this.updateDateDisplay());
        }
        
        if (endDateInput) {
            endDateInput.addEventListener('change', () => this.updateDateDisplay());
        }
    }

    toggleDatePicker() {
        const datePicker = document.getElementById('datePicker');
        if (this.datePickerOpen) {
            this.closeDatePicker();
        } else {
            datePicker.classList.remove('hidden');
            this.datePickerOpen = true;
            
            // Close when clicking outside
            document.addEventListener('click', (event) => this.handleOutsideClick(event));
        }
    }

    closeDatePicker() {
        const datePicker = document.getElementById('datePicker');
        datePicker.classList.add('hidden');
        this.datePickerOpen = false;
        document.removeEventListener('click', this.handleOutsideClick);
    }

    handleOutsideClick(event) {
        const datePicker = document.getElementById('datePicker');
        const dateFilter = event.target.closest('[data-date-filter-toggle]');
        
        if (!datePicker.contains(event.target) && !dateFilter) {
            this.closeDatePicker();
        }
    }

    clearDateFilter() {
        document.getElementById('start_date').value = '';
        document.getElementById('end_date').value = '';
        document.getElementById('dateRangeDisplay').textContent = 'Pilih Tanggal';
        
        // Submit form to clear filters
        const form = document.getElementById('dateFilterForm');
        const url = new URL(form.action);
        url.searchParams.delete('start_date');
        url.searchParams.delete('end_date');
        window.location.href = url.toString();
    }

    updateDateDisplay() {
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;
        const display = document.getElementById('dateRangeDisplay');
        
        if (startDate && endDate) {
            const start = new Date(startDate).toLocaleDateString('id-ID', { 
                day: 'numeric', 
                month: 'short' 
            });
            const end = new Date(endDate).toLocaleDateString('id-ID', { 
                day: 'numeric', 
                month: 'short', 
                year: 'numeric' 
            });
            display.textContent = `${start} - ${end}`;
        } else if (startDate) {
            const start = new Date(startDate).toLocaleDateString('id-ID', { 
                day: 'numeric', 
                month: 'short', 
                year: 'numeric' 
            });
            display.textContent = `${start} - ...`;
        } else {
            display.textContent = 'Pilih Tanggal';
        }
    }

    formatDate(dateString) {
        if (!dateString) return '';
        
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'short',
            year: 'numeric'
        });
    }
}

// Initialize DateFilter when DOM is loaded
document.addEventListener('turbo:load', function() {
    window.dateFilter = new DateFilter();
});

// Global functions for backward compatibility and inline event handlers
window.toggleDatePicker = function() {
    window.dateFilter.toggleDatePicker();
};

window.closeDatePicker = function() {
    window.dateFilter.closeDatePicker();
};

window.clearDateFilter = function() {
    window.dateFilter.clearDateFilter();
};