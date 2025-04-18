import { GetBaseUrl } from './GetBaseUrl.js';

export const ProductSuggestions = (function () {
    const debounce = (func, wait) => {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    };

    const fetchSuggestions = async (query, suggestionsList, inputField, endpoint) => {
        if (query.length < 2) {
            suggestionsList.style.display = 'none';
            suggestionsList.innerHTML = '';
            return;
        }

        const baseUrl = GetBaseUrl();

        try {
            const response = await fetch(`${baseUrl}/handlers/${endpoint}?query=${encodeURIComponent(query)}`);
            const data = await response.json();
            showSuggestions(data, suggestionsList, inputField);
        } catch (error) {
            console.error(`Error fetching ${endpoint} suggestions:`, error);
        }
    };

    const showSuggestions = (items, suggestionsList, inputField) => {
        if (!Array.isArray(items)) {
            console.error('Expected array but got:', items);
            return;
        }

        suggestionsList.innerHTML = items.map(item =>
            `<li class="list-group-item list-group-item-action">${item.nazwa || item.rozmiar}</li>`
        ).join('');
        suggestionsList.style.display = 'block';

        const suggestionItems = suggestionsList.querySelectorAll('li');
        suggestionItems.forEach(item => {
            item.addEventListener('click', () => {
                inputField.value = item.textContent;
                suggestionsList.style.display = 'none';
            });
        });
    };

    return {
        fetchSuggestions,
        showSuggestions,
        debounce
    };
})();
