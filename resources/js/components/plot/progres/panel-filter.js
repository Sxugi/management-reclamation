class ProgresReklamasiFilterPanel {
    constructor() {
        this.open = false;
        this.handleOutsideClickBound = this.handleOutsideClick.bind(this);
        this.bindSubmitCleanup();
    }

    toggle() {
        const panel = document.getElementById('progresReklamasiFilterPanel');
        if (this.open) {
            this.close();
        } else {
            panel.classList.remove('hidden');
            this.open = true;
            document.addEventListener('click', this.handleOutsideClickBound);
        }
    }

    close() {
        const panel = document.getElementById('progresReklamasiFilterPanel');
        if (panel) panel.classList.add('hidden');
        this.open = false;
        document.removeEventListener('click', this.handleOutsideClickBound);
    }

    handleOutsideClick(event) {
        const panel = document.getElementById('progresReklamasiFilterPanel');
        const toggleBtn = event.target.closest('[data-filter-panel-toggle]');
        if (!panel.contains(event.target) && !toggleBtn) {
            this.close();
        }
    }

    clear() {
        document.getElementById('date').value = '';
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
        document.getElementById('category').value = '';
        document.getElementById('hasDokumentasi').checked = false;

        const form = document.getElementById('filterForm');
        const url = new URL(form.action, window.location.origin);
        window.Turbo ? Turbo.visit(url.toString()) : window.location = url.toString();
    }

    bindSubmitCleanup() {
        document.addEventListener("turbo:load", () => {
            const form = document.getElementById("filterForm");
            if (!form) return;

            form.addEventListener("submit", (e) => {
                e.preventDefault();
                const formData = new FormData(form);
                for (const [key, value] of Array.from(formData.entries())) {
                    if (!value || (key === 'hasDokumentasi' && !form.querySelector('#hasDokumentasi').checked)) {
                        formData.delete(key);
                    }
                }
                const query = new URLSearchParams(formData).toString();
                window.Turbo
                    ? Turbo.visit(query ? `${form.action}?${query}` : form.action)
                    : window.location = query ? `${form.action}?${query}` : form.action;
            });
        });
    }
}

document.addEventListener('turbo:load', () => {
    window.progresReklamasiFilterPanel = new ProgresReklamasiFilterPanel();
});

window.toggleProgresReklamasiFilterPanel = function() {
    window.progresReklamasiFilterPanel.toggle();
};
window.closeFilterPanel = function() {
    window.progresReklamasiFilterPanel.close();
};
window.clearFilters = function() {
    window.progresReklamasiFilterPanel.clear();
};