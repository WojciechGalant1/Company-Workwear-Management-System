import { AlertManager } from '../AlertManager.js';

export const LoginValidator = (function () {
    let kodInput = '';
    let alertManager = '';

    let kodValidator = function (kodPole, alertContainer) {
        kodInput = kodPole;
        alertManager = AlertManager.create(alertContainer);

        this.loadingSpinner = document.getElementById('loadingSpinner');
        this.debounceTimeout = null;

        document.getElementById('kodID').addEventListener('change', autoValidateKodLogin.bind(this));

        this.showSpinner = function () {
            this.loadingSpinner.style.display = 'block';
        };

        this.hideSpinner = function () {
            this.loadingSpinner.style.display = 'none';
        };
    };

    let autoValidateKodLogin = function () {
        const kodID = kodInput.value.trim();

        if (kodID.length === 0) {
            alertManager.createAlert('Wprowadź kod.');
            return;
        }

        this.showSpinner();

        const baseUrl = document.querySelector('meta[name="base-url"]').getAttribute('content') || '';

        $.ajax({
            type: 'POST',
            url: baseUrl + '/handlers/auth/validateLogin.php',
            data: { kodID: kodID, csrf: getCsrfToken() },
            success: (data) => {
                if (data.status === 'success') {
                    alertManager.createAlert('Poprawne dane', 'success');
                    window.location.href = baseUrl + '/wydaj-ubranie';
                    this.hideSpinner();
                } else {
                    alertManager.createAlert('Błędny kod');
                    kodInput.value = '';
                    kodInput.focus();
                    this.hideSpinner();
                }
            },
            error: () => {
                alertManager.createAlert('Błąd serwera');
                this.hideSpinner();
            },
        });
    };

    function getCsrfToken() {
        const el = document.querySelector('meta[name="csrf-token"]');
        return el ? el.getAttribute('content') : '';
    }

    return { kodValidator };
})();


