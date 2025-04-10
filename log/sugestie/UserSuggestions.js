import { AlertManager } from './AlertManager.js';

export const UserSuggestions = (function () {
    let UserSuggestions = function (usernameInput, suggestions, alertManager, passwordInput) {
        this.usernameInput = usernameInput;
        this.suggestions = suggestions;
        this.alertManager = alertManager;
        this.passwordInput = passwordInput;
        this.loadingSpinner = document.getElementById('loadingSpinner');

        this.debounce = function (func, wait) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        };

        this.handleInputChange = function () {
            const query = this.usernameInput.value.trim();

            if (query.length >= 3) {
                this.loadingSpinner.style.display = 'block';
            }

            const regex = /[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
            if (regex.test(query)) {
                this.loadingSpinner.style.display = 'none';
                this.alertManager.createAlert('Imię i nazwisko nie może zawierać cyfr ani znaków specjalnych.');
            } else {
                this.fetchSuggestions();
            }
        };

        this.fetchSuggestions = function () {
            const query = this.usernameInput.value.trim();

            if (query.length < 3) {
                this.suggestions.style.display = 'none';
                this.suggestions.innerHTML = '';
                this.alertManager.removeAlert();
                this.loadingSpinner.style.display = 'none';
                return;
            }

            $.ajax({
                type: 'GET',
                url: './sugestie/fetchUsers.php',
                data: { query: query },
                success: (data) => {
                    try {
                        console.log(data);
                        this.showSuggestions(data);
                    } catch (error) {
                        console.error('Nie udało się wczytać danych: ', error);
                    }
                    this.loadingSpinner.style.display = 'none';
                },
                error: (jqXHR, textStatus, errorThrown) => {
                    console.log('Nie udało się wczytać danych');
                    console.log(textStatus, errorThrown);
                    this.loadingSpinner.style.display = 'none';
                },
            });
        };

        this.showSuggestions = function (filteredNames) {
            if (!Array.isArray(filteredNames)) {
                console.error('Expected array but got:', filteredNames);
                return;
            }

            this.suggestions.innerHTML = filteredNames.map(user => `
                <li class="list-group-item list-group-item-action">${user.name} ${user.surname}</li>
            `).join('');
            this.suggestions.style.display = 'block';

            const suggestionItems = this.suggestions.querySelectorAll('li');
            suggestionItems.forEach(item => {
                item.addEventListener('click', () => {
                    this.usernameInput.value = item.textContent;
                    this.suggestions.style.display = 'none';
                    this.alertManager.createAlert(`Wprowadź hasło dla: ${item.textContent}`);
                    this.passwordInput.focus(); 
                });
            });
        };

        this.onInputChange = this.debounce(this.handleInputChange.bind(this), 300);
        this.usernameInput.addEventListener('input', () => this.onInputChange());
    };

    return UserSuggestions;
})();
