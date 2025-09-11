import { formatCurrency, initializeBiayaInputs, updatePlaceholders } from './currency-format.js';

document.addEventListener('turbo:load', function () {
    const currencyDropdown = document.getElementById('currency');
    const convertCurrencyButton = document.getElementById('convertCurrencyBtn');
    const biayaForm = document.querySelector('form[data-currency]');
    const hiddenCurrency = document.getElementById('currencyHidden');
    const currencyLabelEl = document.getElementById('currencyLabel');

    let idrToUsdRate = null;
    let currentCurrency = (currencyDropdown && currencyDropdown.value) || (biayaForm ? biayaForm.dataset.currency : 'IDR');
    window.currentCurrency = currentCurrency;

    function fetchIdrToUsdRate(callback) {
        fetch('https://api.exchangerate-api.com/v4/latest/USD')
            .then(response => response.json())
            .then(data => callback(data.rates?.IDR || null))
            .catch(() => callback(null));
    }

    function updateExchangeRateLabel() {
        const exchangeRateLabel = document.getElementById('exchangeRateText');
        if (!exchangeRateLabel || !idrToUsdRate) return;
        const usdToIdr = 1 / idrToUsdRate;
        if (currentCurrency === 'USD') {
            exchangeRateLabel.textContent = `Current rate: 1 USD = ${idrToUsdRate.toLocaleString('id-ID')} IDR`;
        } else {
            exchangeRateLabel.textContent = `Current rate: 1 IDR = ${usdToIdr.toFixed(6)} USD`;
        }
    }

    function updateCurrencyLabel(currency) {
        const labelLangsung = document.getElementById('currencyLabelLangsung');
        const labelTidakLangsung = document.getElementById('currencyLabelTidakLangsung');
        const label = currency === 'USD' ? 'US$' : (currency === 'IDR' ? 'Rp' : 'Rp/US$');
        if (labelLangsung) labelLangsung.textContent = label;
        if (labelTidakLangsung) labelTidakLangsung.textContent = label;
    }

    function convertAllInputs(fromCurrency, toCurrency) {
        if (!idrToUsdRate || fromCurrency === toCurrency) {
            window.currentCurrency = toCurrency;
            currentCurrency = toCurrency;
            if (biayaForm) biayaForm.dataset.currency = toCurrency;
            if (currencyDropdown) currencyDropdown.value = toCurrency;
            if (hiddenCurrency) hiddenCurrency.value = toCurrency;
            updateCurrencyLabel(toCurrency);
            initializeBiayaInputs(toCurrency);
            updateExchangeRateLabel();
            return;
        }

        document.querySelectorAll('[id^="biaya-input-"], [id^="biaya-tidaklang-input-"], [id^="subtotal-input-"]').forEach(hiddenInput => {
            const rawValue = parseFloat(hiddenInput.value);
            if (isNaN(rawValue)) return;

            let convertedValue;
            if (fromCurrency === 'IDR' && toCurrency === 'USD') {
                convertedValue = (rawValue / idrToUsdRate).toFixed(2);
            } else if (fromCurrency === 'USD' && toCurrency === 'IDR') {
                convertedValue = Math.round(rawValue * idrToUsdRate);
                console.log(convertedValue);
            } else {
                convertedValue = rawValue;
            }

            hiddenInput.value = String(convertedValue);

            const displayId = hiddenInput.id.replace('-input-', '-display-');
            const displayInput = document.getElementById(displayId);

            if (displayInput) {
                displayInput.value = formatCurrency(convertedValue, toCurrency, true);
            }
        });

        window.currentCurrency = toCurrency;
        currentCurrency = toCurrency;
        if (biayaForm) biayaForm.dataset.currency = toCurrency;
        if (currencyDropdown) currencyDropdown.value = toCurrency;
        if (hiddenCurrency) hiddenCurrency.value = toCurrency;
        updateCurrencyLabel(toCurrency);
        updatePlaceholders(toCurrency);

        initializeBiayaInputs(toCurrency);

        try {
            const url = new URL(window.location.href);
            url.searchParams.set('currency', toCurrency);
            window.history.replaceState(null, '', url);
        } catch (_) {}

        updateExchangeRateLabel();
    }

    function initialize() {
        fetchIdrToUsdRate(rate => {
            idrToUsdRate = rate;
            updateCurrencyLabel(currentCurrency);
            initializeBiayaInputs(currentCurrency);
            updateExchangeRateLabel();
        });
    }

    if (currencyDropdown) {
        initialize();

        currencyDropdown.addEventListener('change', function () {
            convertAllInputs(currentCurrency, currencyDropdown.value);
        });
    }

    if (convertCurrencyButton) {
        convertCurrencyButton.addEventListener('click', function () {
            const targetCurrency = currentCurrency === 'IDR' ? 'USD' : 'IDR';
            convertAllInputs(currentCurrency, targetCurrency);
        });
    }
});