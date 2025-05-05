import { GetBaseUrl } from './GetBaseUrl.js';

export const UbraniaKod = (() => {
    const initializeKodInput = (inputElement, alertManager) => {
        const baseUrl = GetBaseUrl();

        inputElement.addEventListener('keydown', async (event) => {
            if (event.key !== 'Enter') return;

            event.preventDefault();
            const kod = inputElement.value.trim();
            const currentRow = inputElement.closest('.ubranieRow');
            const ubranieIdInput = currentRow.querySelector('input[name*="[id_ubrania]"]');
            const rozmiarIdInput = currentRow.querySelector('input[name*="[id_rozmiar]"]');

            if (!kod) {
                ubranieIdInput.value = '';
                rozmiarIdInput.value = '';
                alertManager.createAlert('Pole kodu nie może być puste.');
                return;
            }

            try {
                const response = await fetch(`${baseUrl}/handlers/getUbranieByKod.php?kod=${encodeURIComponent(kod)}`);
                const data = await response.json();

                if (data && !data.error) {
                    ubranieIdInput.value = data.id_ubrania;
                    rozmiarIdInput.value = data.id_rozmiar;
                    alertManager.createAlert(`Znaleziono ubranie: ${data.nazwa_ubrania}, rozmiar: ${data.nazwa_rozmiaru}`);
                } else {
                    ubranieIdInput.value = '';
                    rozmiarIdInput.value = '';
                    alertManager.createAlert('Nie znaleziono ubrania o podanym kodzie.');
                }
            } catch (error) {
                console.error('Wystąpił błąd podczas wyszukiwania ubrania:', error);
                alertManager.createAlert('Wystąpił błąd podczas wyszukiwania ubrania.');
            }
        });
    };

    return { initializeKodInput };
})();
