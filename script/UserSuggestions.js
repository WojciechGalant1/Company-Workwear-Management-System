import { AlertManager } from './AlertManager.js';
import { GetBaseUrl } from './GetBaseUrl.js';

export const UserSuggestions = (function () {
    let UserSuggestions = function (usernameInput, suggestions, alertManager) {
        this.usernameInput = usernameInput;
        this.suggestions = suggestions;
        this.alertManager = alertManager;
        this.loadingSpinnerName = document.getElementById('loadingSpinnerName');
        this.hiddenInput = document.getElementById('pracownikID');
        this.baseUrl = GetBaseUrl();

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
                this.loadingSpinnerName.style.display = 'block';
            }

            const regex = /[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
            if (regex.test(query)) {
                this.loadingSpinnerName.style.display = 'none';
                this.alertManager.createAlert('Imię i nazwisko nie może zawierać cyfr ani znaków specjalnych.');
            } else {
                this.fetchSuggestions();
            }
        };

        this.fetchSuggestions = function () {
            const start = performance.now();
            const query = this.usernameInput.value.trim();

            if (query.length < 3) {
                this.suggestions.style.display = 'none';
                this.suggestions.innerHTML = '';
                this.alertManager.removeAlert();
                this.loadingSpinnerName.style.display = 'none';
                return;
            }

            fetch(`${this.baseUrl}/handlers/fetchUsers.php?query=${encodeURIComponent(query)}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.length === 0) {
                        this.alertManager.createAlert('Nie znaleziono pracownika o podanym imieniu i nazwisku.');
                    } else {
                        this.showSuggestions(data);
                    }
                    this.loadingSpinnerName.style.display = 'none';
                })
                .catch(error => {
                    console.error('Nie udało się wczytać danych:', error);
                    this.loadingSpinnerName.style.display = 'none';
                });
                const end = performance.now();
                console.log(`UserSuggestions fetchSuggestions: ${(end - start)} ms`);
        };

        this.showSuggestions = function (filteredNames) {
            if (!Array.isArray(filteredNames)) {
                console.error('Expected array but got:', filteredNames);
                return;
            }

            this.suggestions.innerHTML = filteredNames.map(user => `
                <li class="list-group-item list-group-item-action" data-id="${user.id_pracownik}">${user.imie} ${user.nazwisko} (${user.stanowisko})</li>
            `).join('');
            this.suggestions.style.display = 'block';

            const suggestionItems = this.suggestions.querySelectorAll('li');
            suggestionItems.forEach(item => {
                item.addEventListener('click', () => {
                    this.usernameInput.value = item.textContent;
                    this.hiddenInput.value = item.dataset.id;
                    this.suggestions.style.display = 'none';
                    this.alertManager.createAlert(`Wybrano pracownika: ${item.textContent}`);
                });
            });
        };

        this.onInputChange = this.debounce(this.handleInputChange.bind(this), 850);

        this.usernameInput.addEventListener('focus', () => {
            this.suggestions.style.display = 'block';
        });

        this.usernameInput.addEventListener('blur', () => {
            setTimeout(() => {
                this.suggestions.style.display = 'none';
            }, 200);
        });

        this.usernameInput.addEventListener('input', () => this.onInputChange());
       
        
    };

    return UserSuggestions;
})();
