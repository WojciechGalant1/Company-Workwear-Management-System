export const AlertManager = (function () {
	let AlertManager = function (alertContainer) {
		this.alertContainer = alertContainer;
		this.alertElement = null;
		this.alertTimeout = null;

		this.createAlert = function (message, styl = 'danger') {
			if (this.alertElement) {
				this.updateAlert(message);
				return;
			}

			this.alertElement = document.createElement('div');
			this.alertElement.classList.add('text-center', 'alert-dismissible', 'fade', 'show');
			if (styl === 'success') {
				this.alertElement.classList.add('alert', 'alert-success');
			} else {
				this.alertElement.classList.add('alert', 'alert-danger');
			}

			this.alertElement.setAttribute('role', 'alert');
			this.alertElement.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
			this.alertContainer.appendChild(this.alertElement);
			const closeButton = this.alertElement.querySelector('.btn-close');
			closeButton.addEventListener('click', () => this.removeAlert());
			this.resetAlertTimer();
		};

		this.updateAlert = function (message) {
			if (this.alertElement) {
				this.alertElement.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
				const closeButton = this.alertElement.querySelector('.btn-close');
				closeButton.addEventListener('click', () => this.removeAlert());
				this.resetAlertTimer();
			}
		};

		this.resetAlertTimer = function () {
			clearTimeout(this.alertTimeout);
			this.alertTimeout = setTimeout(() => this.removeAlert(), 9000);
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
