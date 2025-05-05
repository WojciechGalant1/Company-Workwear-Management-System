export const AlertManager = (function () {
    const create = (alertContainer) => {
        let alertElement = null;
        let alertTimeout = null;

        const removeAlert = () => {
            if (alertElement) {
                alertElement.remove();
                alertElement = null;
                clearTimeout(alertTimeout);
            }
        };

        const resetAlertTimer = () => {
            clearTimeout(alertTimeout);
            alertTimeout = setTimeout(removeAlert, 6000);
        };

        const getAlertHTML = (message) => {
            return `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
        };

        const attachCloseListener = () => {
            const closeButton = alertElement.querySelector('.btn-close');
            if (closeButton) {
                closeButton.addEventListener('click', removeAlert);
            }
        };

        const styleAlert = () => {
            Object.assign(alertElement.style, {
                position: 'fixed',
                top: '10%',
                left: '50%',
                transform: 'translateX(-50%)',
                zIndex: '9999'
            });
        };

        const setAlertContent = (message, type) => {
            alertElement.className = `alert alert-${type} alert-dismissible fade show`;
            alertElement.innerHTML = getAlertHTML(message);
            attachCloseListener();
            resetAlertTimer();
        };

        const updateAlert = (message, type = 'secondary') => {
            if (alertElement) {
                setAlertContent(message, type);
            }
        };

        const createAlert = (message, type = 'info') => {
            if (alertElement) {
                updateAlert(message, type);
                return;
            }

            alertElement = document.createElement('div');
            alertElement.setAttribute('role', 'alert');
            alertContainer.appendChild(alertElement);

            styleAlert();
            setAlertContent(message, type);
        };

        return {
            createAlert,
            updateAlert,
            removeAlert
        };
    };

    return {
        create
    };
})();
