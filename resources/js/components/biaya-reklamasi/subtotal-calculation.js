function calculateSubtotals() {
    // calculate SUBTOTAL 1 (Biaya Langsung)
    let subtotal1 = 0;
    document.querySelectorAll('[id^="biaya-input-"]').forEach(input => {
        const kategori = input.closest('form')
            .querySelector(`input[name="${input.name.replace('[biaya]', '[kategori]')}"]`);
        if (kategori && kategori.value === 'biaya_langsung') {
            subtotal1 += parseFloat(input.value || 0);
        }
    });
    
    // Set SUBTOTAL 1
    const subtotal1Input = document.getElementById('subtotal-input-subtotal_1');
    const subtotal1Display = document.getElementById('subtotal-display-subtotal_1');
    if (subtotal1Input) {
        subtotal1Input.value = subtotal1;
        subtotal1Display.value = formatCurrency(subtotal1, window.currentCurrency);
    }
    
    // calculate Total Biaya Tidak Langsung
    let biayaTidakLangsung = 0;
    document.querySelectorAll('[id^="biaya-tidaklang-input-"]').forEach(input => {
        biayaTidakLangsung += parseFloat(input.value || 0);
    });
    
    // calculate SUBTOTAL 2 (GRAND TOTAL)
    const subtotal2 = biayaTidakLangsung;
    const subtotal2Input = document.getElementById('subtotal-input-subtotal_2');
    const subtotal2Display = document.getElementById('subtotal-display-subtotal_2');
    if (subtotal2Input) {
        subtotal2Input.value = subtotal2;
        subtotal2Display.value = formatCurrency(subtotal2, window.currentCurrency);
    }
}

// Trigger calculation on blur event of relevant inputs
document.addEventListener('turbo:load', function() {
    document.querySelectorAll('[id^="biaya-display-"], [id^="biaya-tidaklang-display-"]')
        .forEach(input => {
            input.addEventListener('blur', calculateSubtotals);
        });
});