import { GetBaseUrl } from './GetBaseUrl.js';

export const CheckUbranie = (() => {
    const baseUrl = GetBaseUrl();

    const toggleIloscMinField = (field, show) => {
        field.style.display = show ? 'block' : 'none';
        field.querySelector('input').disabled = !show;
    };

    const attachSuggestionHandlers = (inputElement, suggestionsList, validate) => {
        let suggestionClicked = false;

        inputElement.addEventListener('input', () => {
            suggestionClicked = false;
        });

        inputElement.addEventListener('blur', () => {
            setTimeout(() => {
                if (!suggestionClicked) validate();
            }, 200);
        });

        if (suggestionsList) {
            suggestionsList.addEventListener('mousedown', (e) => {
                if (e.target.tagName === 'LI') {
                    suggestionClicked = true;
                    inputElement.value = e.target.textContent.trim();
                }
            });

            suggestionsList.addEventListener('mouseup', () => {
                setTimeout(() => validate(), 0);
            });
        }
    };

    const checkKod = (inputElement, alertManager) => {
        const row = inputElement.closest('.ubranieRow');
        const iloscMinField = row.querySelector('input[name*="[iloscMin]"]').closest('.col-md-2');

        const validate = async () => {
            const kod = inputElement.value.trim();
            if (!kod) return;

            try {
                const response = await fetch(`${baseUrl}/handlers/getUbranieByKod.php?kod=${encodeURIComponent(kod)}`);
                const data = await response.json();

                if (data && !data.error) {
                    alertManager.createAlert(`Znaleziono ubranie: ${data.nazwa_ubrania}, rozmiar: ${data.nazwa_rozmiaru}`);
                    toggleIloscMinField(iloscMinField, false);
                    row.dataset.ubrFoundByKod = 'true';
                    row.querySelector('input[name$="[nazwa]"]').value = data.nazwa_ubrania;
                    row.querySelector('input[name$="[rozmiar]"]').value = data.nazwa_rozmiaru;
                } else {
                    alertManager.createAlert('Nie znaleziono ubrania o podanym kodzie.');
                    toggleIloscMinField(iloscMinField, true);
                    row.dataset.ubrFoundByKod = 'false';
                }
            } catch (error) {
                console.error('Błąd przy sprawdzaniu magazynu:', error);
                alertManager.createAlert('Wystąpił błąd podczas sprawdzania kodu.');
            }
        };

        const suggestionsList = row.querySelector('#codeSuggestions');
        attachSuggestionHandlers(inputElement, suggestionsList, validate);
    };

    const checkNameSize = (inputElement, alertManager) => {
        const row = inputElement.closest('.ubranieRow');
        const iloscMinField = row.querySelector('input[name*="[iloscMin]"]').closest('.col-md-2');

        const validate = async () => {
            if (row.dataset.ubrFoundByKod === 'true') return;

            const productName = row.querySelector('input[name*="[nazwa]"]').value.trim();
            const sizeName = row.querySelector('input[name*="[rozmiar]"]').value.trim();
            if (!productName || !sizeName) return;

            try {
                const response = await fetch(`${baseUrl}/handlers/checkUbranieExists.php?nazwa=${encodeURIComponent(productName)}&rozmiar=${encodeURIComponent(sizeName)}`);
                const data = await response.json();

                if (data.exists) {
                    alertManager.createAlert('Ubranie o tej nazwie i rozmiarze istnieje w magazynie.');
                    toggleIloscMinField(iloscMinField, false);
                } else {
                    alertManager.createAlert('Ubranie o tej nazwie i rozmiarze nie istnieje w magazynie.');
                    toggleIloscMinField(iloscMinField, true);
                }
            } catch (error) {
                console.error('Błąd przy sprawdzaniu magazynu:', error);
            }
        };

        const suggestionsList = row.querySelector(
            inputElement.name.includes('nazwa') ? '#productSuggestions' : '#sizeSuggestions'
        );
        attachSuggestionHandlers(inputElement, suggestionsList, validate);
    };

    return { checkKod, checkNameSize };
})();
