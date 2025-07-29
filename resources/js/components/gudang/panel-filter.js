class GudangFilterPanel {
    constructor() {
        this.open = false;
        this.handleOutsideClickBound = this.handleOutsideClick.bind(this);
        this.bindSubmitCleanup();
    }

    toggle() {
        const panel = document.getElementById('gudangFilterPanel');
        if (this.open) {
            this.close();
        } else {
            panel.classList.remove('hidden');
            this.open = true;
            document.addEventListener('click', this.handleOutsideClickBound);
        }
    }

    close() {
        const panel = document.getElementById('gudangFilterPanel');
        if (panel) panel.classList.add('hidden');
        this.open = false;
        document.removeEventListener('click', this.handleOutsideClickBound);
    }

    handleOutsideClick(event) {
        const panel = document.getElementById('gudangFilterPanel');
        const toggleBtn = event.target.closest('[data-filter-panel-toggle]');
        if (!panel.contains(event.target) && !toggleBtn) {
            this.close();
        }
    }

    clear() {
        document.getElementById('startDate').value = '';
        document.getElementById('endDate').value = '';
        document.getElementById('jenisBarang').value = '';
        document.getElementById('statusBarang').value = '';
        const form = document.getElementById('gudangFilterForm');
        const url = new URL(form.action);
        Turbo.visit(url.toString());
    }

    bindSubmitCleanup() {
        document.addEventListener("turbo:load", () => {
            const form = document.getElementById("gudangFilterForm");
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
    window.gudangFilter = new GudangFilterPanel();
});

window.toggleGudangFilterPanel = () => window.gudangFilter.toggle();
window.closeGudangFilterPanel = () => window.gudangFilter.close();
window.clearGudangFilters = () => window.gudangFilter.clear();
