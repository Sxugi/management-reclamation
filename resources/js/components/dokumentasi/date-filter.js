class DateFilter {
    constructor() {
        this.datePickerOpen = false;
        this.handleOutsideClickBound = this.handleOutsideClick.bind(this);
        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.bindEvents();
        });
    }

    bindEvents() {
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
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
            if (datePicker) datePicker.classList.remove('hidden');
            this.datePickerOpen = true;
            document.addEventListener('click', this.handleOutsideClickBound);
        }
    }

    closeDatePicker() {
        const datePicker = document.getElementById('datePicker');
        if (datePicker) datePicker.classList.add('hidden');
        this.datePickerOpen = false;
        document.removeEventListener('click', this.handleOutsideClickBound);
    }

    handleOutsideClick(event) {
        const datePicker = document.getElementById('datePicker');
        if (!datePicker) {
            this.closeDatePicker();
            return;
        }
        const dateFilter = event.target.closest('[data-date-filter-toggle]');
        if (!datePicker.contains(event.target) && !dateFilter) {
            this.closeDatePicker();
        }
    }

    clearDateFilter() {
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
        document.getElementById('dateRangeDisplay').textContent = 'Pilih Tanggal';
        const form = document.getElementById('dateFilterForm');
        const url = new URL(form.action);
        url.searchParams.delete('startDate');
        url.searchParams.delete('endDate');
        window.location.href = url.toString();
    }

    updateDateDisplay() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        const display = document.getElementById('dateRangeDisplay');
        if (startDate && endDate) {
            const start = new Date(startDate).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
            const end = new Date(endDate).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            display.textContent = `${start} - ${end}`;
        } else if (startDate) {
            const start = new Date(startDate).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
            display.textContent = `${start} - ...`;
        } else {
            display.textContent = 'Pilih Tanggal';
        }
    }

    formatDate(dateString) {
        if (!dateString) return '';
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
    }
}

document.addEventListener('turbo:load', function() {
    window.dateFilter = new DateFilter();
});

window.toggleDatePicker = function() {
    window.dateFilter.toggleDatePicker();
};
window.closeDatePicker = function() {
    window.dateFilter.closeDatePicker();
};
window.clearDateFilter = function() {
    window.dateFilter.clearDateFilter();
};