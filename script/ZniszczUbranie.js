import { getBaseUrl, addCsrfToObject } from './utils.js';

export const ZniszczUbranie = (function () {
    let ubranieId = null;
    let selectedButton = null;

    const destroy = async () => {
        const baseUrl = getBaseUrl();

        try {
            const requestData = addCsrfToObject({ id: ubranieId });
            
            const response = await fetch(`${baseUrl}/handlers/zniszcz_ubranie.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestData)
            });

            const data = await response.json();

            if (data.success) {
                selectedButton.disabled = true;
                selectedButton.textContent = "Status zmieniony";
                window.location.reload();
            } else {
                alert('Błąd podczas usuwania zniszczonego ubrania.');
            }
        } catch (error) {
            console.error('Błąd:', error);
            alert('Wystąpił błąd podczas przesyłania żądania.');
        }
    };

    const initialize = () => {
        const informButtons = document.querySelectorAll('.destroy-btn');

        informButtons.forEach(button => {
            button.addEventListener('click', () => {
                ubranieId = button.getAttribute('data-id');
                selectedButton = button;
                $('#confirmDestroyModal').modal('show');
            });
        });

        document.getElementById('confirmDestroyBtn').addEventListener('click', () => {
            destroy();
            $('#confirmDestroyModal').modal('hide');
        });
    };

    return { initialize };
})();
