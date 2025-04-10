import { AlertManager } from './AlertManager.js';
import { getBaseUrl } from './GetBaseUrl.js';

export const UbraniaKod = (function () {
    const initializeKodInput = function(inputElement, alertManager) {
        const baseUrl = getBaseUrl();
        
        inputElement.addEventListener('keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const kod = inputElement.value.trim();

                if (kod.length > 0) {
                    fetch(`${baseUrl}/handlers/getUbranieByKod.php?kod=${encodeURIComponent(kod)}`)
                        .then(response => response.json())
                        .then(data => {
                            const currentRow = inputElement.closest('.ubranieRow');
                            const ubranieIdInput = currentRow.querySelector('input[name*="[id_ubrania]"]');
                            const rozmiarIdInput = currentRow.querySelector('input[name*="[id_rozmiar]"]');

                            if (data && !data.error) { 
                                ubranieIdInput.value = data.id_ubrania;
                                rozmiarIdInput.value = data.id_rozmiar;

                                alertManager.createAlert(`Znaleziono ubranie: ${data.nazwa_ubrania}, rozmiar: ${data.nazwa_rozmiaru}`);
                            } else { 
                                ubranieIdInput.value = '';  
                                rozmiarIdInput.value = '';
                                alertManager.createAlert('Nie znaleziono ubrania o podanym kodzie.');
                            }
                        })
                        .catch(error => {
                            console.error('Wystąpił błąd podczas wyszukiwania ubrania:', error);
                            alertManager.createAlert('Wystąpił błąd podczas wyszukiwania ubrania.');
                        });
                } else {
                    const currentRow = inputElement.closest('.ubranieRow');
                    const ubranieIdInput = currentRow.querySelector('input[name*="[id_ubrania]"]');
                    const rozmiarIdInput = currentRow.querySelector('input[name*="[id_rozmiar]"]');
                    ubranieIdInput.value = '';
                    rozmiarIdInput.value = '';

                    alertManager.createAlert('Pole kodu nie może być puste.');
                }
            }
        });
    };

    return {
        initializeKodInput
    };
})();


