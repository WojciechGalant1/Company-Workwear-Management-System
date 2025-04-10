import { AlertManager } from '../sugestie/AlertManager.js';

export const LoginValidator = (function () {

	let kodInput = '';
	let alertManager = '';

	let kodValidator = function (kodPole, alertContainer) {
		console.log('Jestem ' + kodPole);
		kodInput = kodPole;
		alertManager = new AlertManager(alertContainer);

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
		console.log('Kod ID do wysłania:', kodID); 
		this.showSpinner();
		console.log('wprowadznie kodu');

		$.ajax({
			type: 'POST',
			url: './sesja/validateLogin.php',
			data: { kodID: kodID },
			success: (data) => {
				console.log('Odpowiedź z serwera:', data);
				if (data.status === 'success') {
					console.log('OK');
					alertManager.createAlert('Poprawne dane', 'success');
					const baseUrl = '../../ubrania/';
					window.location.href = baseUrl + 'wydaj-ubranie';
					this.hideSpinner();
				} else {
					console.log('NOK');
					console.log(kodInput);
					alertManager.createAlert('Błędny kod');
					kodInput.value = '';
					kodInput.focus();
					this.hideSpinner();
				}
			},
			error: (jqXHR, textStatus, errorThrown) => {
				console.log('Nie udało się wczytać danych');
				console.log(textStatus, errorThrown);
				this.hideSpinner();
			},
		});
	};

	return { kodValidator };
})();

// this.debounceValidate = function () {
//     //spiner wczytywani
//     //wywołaj ajax
//     //this.autoValidateKodLogin();
//     // clearTimeout(this.debounceTimeout);
//     // this.debounceTimeout = setTimeout(() => this.autoValidateKodLogin(), 300); // 300ms opóźnienia
// };

// this.kodInput.addEventListener('input', () => this.debounceValidate());

/* 
		(usernameInput, passwordInput, kodInput, alertManager)
		this.usernameInput = usernameInput;
		this.passwordInput = passwordInput;
		this.loginButton = document.querySelector('button[name="log"]');
		this.kodLoginButton = document.querySelector('button[name="logKod"]');
		this.loginButton.addEventListener('click', () => this.validateLogin());
 */

/*
//logowanie za pomocą login,hasło

	   this.validateLogin = function () {
		   const username = this.usernameInput.value.trim();
		   const password = this.passwordInput.value.trim();

		   const regex = /[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;

		   if (username.length < 3) {
			   this.alertManager.createAlert('Imię i nazwisko oraz hasło muszą mieć co najmniej 3 znaki.');
			   return;
		   }

		   if (regex.test(username)) {
			   this.alertManager.createAlert('Imię i nazwisko nie może zawierać cyfr ani znaków specjalnych.');
			   return;
		   }

		   if (password.length === 0) {
			   this.alertManager.createAlert('Wprowadź hasło.');
			   return;
		   }

		   this.showSpinner();

		   $.ajax({
			   type: 'POST',
			   url: './sesja/validateLogin.php',
			   data: { username: username, password: password },
			   success: (data) => {
				   console.log('Odpowiedź z serwera:', data); 
				   if (data.status === 'success') {
					   this.alertManager.createAlert('Poprawne dane');
					   this.redirectToPage('../index.php', 100); 
				   } else {
					   this.alertManager.createAlert('Błędne dane');
					   this.hideSpinner();
				   }
			   },
			   error: (jqXHR, textStatus, errorThrown) => {
				   console.log('Nie udało się wczytać danych');
				   console.log(textStatus, errorThrown);
				   this.hideSpinner();
			   },
		   });
	   };
	   */