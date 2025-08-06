import { getBaseUrl } from './utils.js';
import { debounce } from './utils.js';

export const WorkerSuggestions = (() => {
    const showSuggestions = (filteredNames, suggestions, usernameInput, hiddenInput, alertManager) => {
        if (!Array.isArray(filteredNames)) {
            console.error('Expected array but got:', filteredNames);
            return;
        }

        suggestions.innerHTML = filteredNames.map(user =>
            `<li class="list-group-item list-group-item-action" data-id="${user.id_pracownik}">${user.imie} ${user.nazwisko} (${user.stanowisko})</li>`
        ).join('');
        suggestions.style.display = 'block';

        suggestions.querySelectorAll('li').forEach(item => {
            item.addEventListener('click', () => {
                usernameInput.value = item.textContent;
                hiddenInput.value = item.dataset.id;
                suggestions.style.display = 'none';
                alertManager.createAlert(`Wybrano pracownika: ${item.textContent}`);
            });
        });
    };

    const cache = {};

    const fetchSuggestions = async (query, baseUrl, suggestions, usernameInput, hiddenInput, alertManager, loadingSpinner) => {
        if (query.length < 3) {
            suggestions.style.display = 'none';
            suggestions.innerHTML = '';
            alertManager.removeAlert();
            loadingSpinner.style.display = 'none';
            return;
        }

        if (cache[query]) {
            showSuggestions(cache[query], suggestions, usernameInput, hiddenInput, alertManager);
            loadingSpinner.style.display = 'none';
            return;
        }

        try {
            const response = await fetch(`${baseUrl}/handlers/fetchWorkers.php?query=${encodeURIComponent(query)}`);
            if (!response.ok) throw new Error('Network response was not ok');
            
            const data = await response.json();
            cache[query] = data;

            if (data.length === 0) {
                alertManager.createAlert('Nie znaleziono pracownika o podanym imieniu i nazwisku.');
            } else {
                showSuggestions(data, suggestions, usernameInput, hiddenInput, alertManager);
            }
        } catch (error) {
            console.error('Nie udało się wczytać danych:', error);
        } finally {
            loadingSpinner.style.display = 'none';
        }
    };

    const create = (usernameInput, suggestions, alertManager) => {
        const baseUrl = getBaseUrl();
        const hiddenInput = document.getElementById('pracownikID');
        const loadingSpinner = document.getElementById('loadingSpinnerName');

        const handleInputChange = () => {
            const query = usernameInput.value.trim();

            if (query.length >= 3) {
                loadingSpinner.style.display = 'block';
            }

            const invalidCharPattern = /[0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/;
            if (invalidCharPattern.test(query)) {
                loadingSpinner.style.display = 'none';
                alertManager.createAlert('Imię i nazwisko nie może zawierać cyfr ani znaków specjalnych.');
            } else {
                fetchSuggestions(query, baseUrl, suggestions, usernameInput, hiddenInput, alertManager, loadingSpinner);
            }
        };

        const onInputChange = debounce(handleInputChange, 850);

        usernameInput.addEventListener('focus', () => {
            suggestions.style.display = 'block';
        });

        usernameInput.addEventListener('blur', () => {
            setTimeout(() => {
                suggestions.style.display = 'none';
            }, 200);
        });

        usernameInput.addEventListener('input', onInputChange);
    };

    return { create };
})();
