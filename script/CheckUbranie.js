import { GetBaseUrl } from './GetBaseUrl.js';

export const CheckUbranie = (function () {
    const checkKod = function (inputElement, alertManager) {
        let suggestionClicked = false;
        const baseUrl = GetBaseUrl();
    
        const validate = () => {
            const kod = inputElement.value.trim();
            const row = inputElement.closest('.ubranieRow');
            const iloscMinField = row.querySelector('input[name*="[iloscMin]"]').closest('.col-md-2');
    
            if (kod.length > 0) {
                fetch(`${baseUrl}/handlers/getUbranieByKod.php?kod=${encodeURIComponent(kod)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && !data.error) {
                            alertManager.createAlert(`Znaleziono ubranie: ${data.nazwa_ubrania}, rozmiar: ${data.nazwa_rozmiaru}`);
    
                            iloscMinField.style.display = 'none';
                            iloscMinField.querySelector('input').disabled = true;
    
                            row.dataset.ubrFoundByKod = 'true';
                            row.querySelector('input[name$="[nazwa]"]').value = data.nazwa_ubrania;
                            row.querySelector('input[name$="[rozmiar]"]').value = data.nazwa_rozmiaru;
                        } else {
                            alertManager.createAlert(`Nie znaleziono ubrania o podanym kodzie.`);
                            iloscMinField.style.display = 'block';
                            iloscMinField.querySelector('input').disabled = false;
    
                            row.dataset.ubrFoundByKod = 'false';
                        }
                    })
                    .catch(error => {
                        console.error('Błąd przy sprawdzaniu magazynu:', error);
                        alertManager.createAlert('Wystąpił błąd podczas sprawdzania kodu.');
                    });
            }
        };
    
        inputElement.addEventListener('blur', () => {
            setTimeout(() => {
                if (!suggestionClicked) {
                    validate();
                }
            }, 200);
        });
    
        const suggestionsList = inputElement.closest('.ubranieRow').querySelector('#codeSuggestions');
        if (suggestionsList) {
            suggestionsList.addEventListener('mousedown', (event) => {
                if (event.target.tagName === 'LI') {
                    suggestionClicked = true;
                    inputElement.value = event.target.textContent.trim();
                }
            });
    
            suggestionsList.addEventListener('mouseup', () => {
                setTimeout(() => validate(), 0);
            });
        }
    };

    const checkNameSize = function (inputElement, alertManager) {
        let suggestionClicked = false;
        const baseUrl = GetBaseUrl();
    
        const validate = () => {
            const row = inputElement.closest('.ubranieRow');
    
            if (row.dataset.ubrFoundByKod === 'true') {
                return;
            }
    
            const productName = row.querySelector('input[name*="[nazwa]"]').value.trim();
            const sizeName = row.querySelector('input[name*="[rozmiar]"]').value.trim();
            const iloscMinField = row.querySelector('input[name*="[iloscMin]"]').closest('.col-md-2'); 
    
            if (productName && sizeName) {
                fetch(`${baseUrl}/handlers/checkUbranieExists.php?nazwa=${encodeURIComponent(productName)}&rozmiar=${encodeURIComponent(sizeName)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            alertManager.createAlert(`Ubranie o tej nazwie i rozmiarze istnieje w magazynie.`);
                            iloscMinField.style.display = 'none';
                            iloscMinField.querySelector('input').disabled = true;
                        } else {
                            alertManager.createAlert(`Ubranie o tej nazwie i rozmiarze nie istnieje w magazynie.`);
                            iloscMinField.style.display = 'block';
                            iloscMinField.querySelector('input').disabled = false;
                        }
                    })
                    .catch(error => {
                        console.error('Błąd przy sprawdzaniu magazynu:', error);
                    });
            }
        };
    
        inputElement.addEventListener('input', () => {
            suggestionClicked = false;
        });
    
        inputElement.addEventListener('blur', () => {
            setTimeout(() => {
                if (!suggestionClicked) {
                    validate();
                }
            }, 200);
        });
    
        const suggestionsList = inputElement.closest('.ubranieRow').querySelector(
            inputElement.name.includes('nazwa') ? '#productSuggestions' : '#sizeSuggestions'
        );
    
        if (suggestionsList) {
            suggestionsList.addEventListener('mousedown', (event) => {
                if (event.target.tagName === 'LI') {
                    suggestionClicked = true;
                    inputElement.value = event.target.textContent.trim();
                }
            });
    
            suggestionsList.addEventListener('mouseup', () => {
                setTimeout(() => validate(), 0);
            });
        }
    };
    
    return {
        checkKod,
        checkNameSize
    };
})();
