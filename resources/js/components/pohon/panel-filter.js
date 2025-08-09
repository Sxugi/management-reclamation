class PohonFilterPanel {
    constructor() {
        this.open = false;
        this.handleOutsideClickBound = this.handleOutsideClick.bind(this);
        this.bindSubmitCleanup();
    }

    toggle() {
        const panel = document.getElementById('pohonFilterPanel');
        if (this.open) {
            this.close();
        } else {
            panel.classList.remove('hidden');
            this.open = true;
            document.addEventListener('click', this.handleOutsideClickBound);
        }
    }

    close() {
        const panel = document.getElementById('pohonFilterPanel');
        if (panel) panel.classList.add('hidden');
        this.open = false;
        document.removeEventListener('click', this.handleOutsideClickBound);
    }

    handleOutsideClick(event) {
        const panel = document.getElementById('pohonFilterPanel');
        const toggleBtn = event.target.closest('[data-filter-panel-toggle]');
        if (!panel.contains(event.target) && !toggleBtn) {
            this.close();
        }
    }

    clear() {
        document.getElementById('startYear').value = '';
        document.getElementById('endYear').value = '';
        document.getElementById('tahun').value = '';
        document.getElementById('pohonType').value = '';
        document.getElementById('minQuantity').value = '';
        const form = document.getElementById('pohonFilterForm');
        const url = new URL(form.action);
        Turbo.visit(url.toString());
    }

    bindSubmitCleanup() {
        document.addEventListener("turbo:load", () => {
            const form = document.getElementById("pohonFilterForm");
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
    window.pohonFilter = new PohonFilterPanel();
});

window.togglePohonFilterPanel = function() {
    window.pohonFilter.toggle();
};
window.closePohonFilterPanel = function() {
    window.pohonFilter.close();
};
window.clearPohonFilters = function() {
    window.pohonFilter.clear();
};