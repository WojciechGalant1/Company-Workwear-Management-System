export const AlertManager = (function () {
    let AlertManager = function (alertContainer) {
        this.alertContainer = alertContainer;
        this.alertElement = null;
        this.alertTimeout = null;

        this.createAlert = function (message, type = 'info') {

            if (this.alertElement) {
                this.updateAlert(message, type);
                return;
            }

            this.alertElement = document.createElement('div');
            this.alertElement.classList.add('alert', `alert-${type}`, 'alert-dismissible', 'fade', 'show');
            this.alertElement.setAttribute('role', 'alert');
            this.alertElement.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
            this.alertContainer.appendChild(this.alertElement);

            this.alertElement.style.position = 'fixed';
            this.alertElement.style.top = '10%';
            this.alertElement.style.left = '50%';
            this.alertElement.style.transform = 'translateX(-50%)';
            this.alertElement.style.zIndex = '9999';
                
            const closeButton = this.alertElement.querySelector('.btn-close');
            closeButton.addEventListener('click', () => this.removeAlert());

            this.resetAlertTimer();
        };

        this.updateAlert = function (message, type = 'secondary') {
            if (this.alertElement) {
                this.alertElement.className = `alert alert-${type} alert-dismissible fade show`;
                this.alertElement.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
                const closeButton = this.alertElement.querySelector('.btn-close');
                closeButton.addEventListener('click', () => this.removeAlert());
                this.resetAlertTimer();
            }
        };

        this.resetAlertTimer = function () {
            clearTimeout(this.alertTimeout);
            this.alertTimeout = setTimeout(() => this.removeAlert(), 6000);
        };

        this.removeAlert = function () {
            if (this.alertElement) {
                this.alertElement.remove();
                this.alertElement = null;
                clearTimeout(this.alertTimeout);
            }
        };
    };

    return AlertManager;
})();
