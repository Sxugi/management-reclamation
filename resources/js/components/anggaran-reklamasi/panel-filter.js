class AnggaranFilterPanel {
    constructor() {
        this.open = false;
        this.handleOutsideClickBound = this.handleOutsideClick.bind(this);
        this.bindSubmitCleanup();
    }

    toggle() {
        const panel = document.getElementById('anggaranFilterPanel');
        if (this.open) {
            this.close();
        } else {
            panel.classList.remove('hidden');
            this.open = true;
            document.addEventListener('click', this.handleOutsideClickBound);
        }
    }

    close() {
        const panel = document.getElementById('anggaranFilterPanel');
        if (panel) panel.classList.add('hidden');
        this.open = false;
        document.removeEventListener('click', this.handleOutsideClickBound);
    }

    handleOutsideClick(event) {
        const panel = document.getElementById('anggaranFilterPanel');
        const toggleBtn = event.target.closest('[data-filter-panel-toggle]');
        if (!panel.contains(event.target) && !toggleBtn) {
            this.close();
        }
    }

    clear() {
        const idsToClear = [
            'startYear','endYear','startMonth','endMonth',
            'minNominal','maxNominal','minTotal',
            'kategori_anggaran','startQuarter','endQuarter','quarter'
        ];
        idsToClear.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });

        const form = document.getElementById('anggaranFilterForm');
        const tabInput = document.getElementById('filterTabAktif');
        const currentParams = new URLSearchParams(window.location.search);
        idsToClear.forEach(id => currentParams.delete(id));
        if (tabInput && tabInput.value) {
            currentParams.set('tab', tabInput.value);
        }
        const url = `${form.action}?${currentParams.toString()}`;
        Turbo.visit(url);
    }

    bindSubmitCleanup() {
        document.addEventListener("turbo:load", () => {
            const form = document.getElementById("anggaranFilterForm");
            if (!form) return;

            form.addEventListener("submit", (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                for (const [key, value] of Array.from(formData.entries())) {
                    if (!value) {
                        formData.delete(key);
                    }
                }
                const query = new URLSearchParams(formData).toString();
                Turbo.visit(query ? `${form.action}?${query}` : form.action);
            });
        });
    }
}

document.addEventListener('turbo:load', () => {
    window.anggaranFilter = new AnggaranFilterPanel();
});

window.toggleAnggaranFilterPanel = function() {
    window.anggaranFilter.toggle();
};
window.closeAnggaranFilterPanel = function() {
    window.anggaranFilter.close();
};
window.clearAnggaranFilters = function() {
    window.anggaranFilter.clear();
};