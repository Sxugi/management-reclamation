export function formatCurrency(number, currency, onBlur = false) {
    if (!number) return '';
    number = number.toString().replace(/[^0-9.]/g, '');

    currency = currency || window.currentCurrency || 'IDR';

    if (currency === 'USD') {
        let num = parseFloat(number);
        if (isNaN(num)) return '';

        if (!onBlur) {
            return '$' + number;
        }

        return '$' + num.toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    let num = parseInt(number);
    if (isNaN(num)) return '';
    return 'Rp. ' + num.toLocaleString('id-ID');
}

function parseCurrency(formatted, currency) {
    currency = currency || window.currentCurrency || 'IDR';
    if (!formatted) return '';
    if (currency === 'USD') {
        return formatted.replace(/[^0-9.]/g, '');
    }
    return formatted.replace(/[^0-9]/g, '');
}

function attachCurrencyHandler(displayInput, hiddenInput) {
    if (hiddenInput.value) {
        displayInput.value = formatCurrency(hiddenInput.value, window.currentCurrency, true);
    }

    displayInput.addEventListener('input', function (e) {
        let rawValue = e.target.value;
        let numericValue = parseCurrency(rawValue, window.currentCurrency);

        if (numericValue && !isNaN(parseFloat(numericValue))) {
            displayInput.value = formatCurrency(numericValue, window.currentCurrency, false);
            hiddenInput.value = numericValue;
        } else {
            displayInput.value = '';
            hiddenInput.value = '';
        }
    });

    displayInput.addEventListener('blur', function () {
        if (hiddenInput.value) {
            displayInput.value = formatCurrency(hiddenInput.value, window.currentCurrency, true);
        }
    });

    displayInput.addEventListener('focus', function (e) {
        setTimeout(() => {
            e.target.setSelectionRange(e.target.value.length, e.target.value.length);
        }, 10);
    });
}

export function updatePlaceholders(currency) {
    const displayInputs = document.querySelectorAll(
        '[id^="biaya-display-"], [id^="biaya-tidaklang-display-"], [id^="subtotal-display-"]'
    );
    displayInputs.forEach(input => {
        input.placeholder = currency === 'USD' ? 'US$' : 'Rp';
    });
}

export function initializeBiayaInputs(currency) {
    window.currentCurrency = currency;

    const mappings = [
        { selector: '[id^="biaya-display-"]', prefix: 'biaya-input-' },
        { selector: '[id^="subtotal-display-"]', prefix: 'subtotal-input-' },
        { selector: '[id^="biaya-tidaklang-display-"]', prefix: 'biaya-tidaklang-input-' },
        { selector: '#subtotal-display-subtotal_2', prefix: 'subtotal-input-subtotal_2', single: true }
    ];

    mappings.forEach(map => {
        if (map.single) {
            const displayInput = document.querySelector(map.selector);
            const hiddenInput = document.getElementById(map.prefix);
            if (displayInput && hiddenInput) {
                attachCurrencyHandler(displayInput, hiddenInput);
            }
        } else {
            document.querySelectorAll(map.selector).forEach(displayInput => {
                const key = displayInput.id.replace(map.selector.replace('[id^="', '').replace('"]', ''), '');
                const hiddenInput = document.getElementById(map.prefix + key);
                if (hiddenInput) {
                    attachCurrencyHandler(displayInput, hiddenInput);
                }
            });
        }
    });

    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function (e) {
            let valid = true;
            document.querySelectorAll('[id^="biaya-input-"], [id^="subtotal-input-"], [id^="biaya-tidaklang-input-"]').forEach(hiddenInput => {
                if (!hiddenInput.value || isNaN(parseFloat(hiddenInput.value))) {
                    valid = false;
                }
            });

            const subtotal2Input = document.getElementById('subtotal-input-subtotal_2');
            if (subtotal2Input && (!subtotal2Input.value || isNaN(parseFloat(subtotal2Input.value)))) {
                valid = false;
            }

            if (!valid) {
                e.preventDefault();
                return false;
            }
        });
    }
}

window.initializeBiayaInputs = initializeBiayaInputs;
window.formatCurrency = formatCurrency;

document.addEventListener('turbo:load', function () {
    const form = document.querySelector('form[data-currency]');
    if (form) {
        const currency = form.dataset.currency;
        initializeBiayaInputs(currency);
    }
});