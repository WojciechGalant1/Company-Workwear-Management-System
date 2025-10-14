import { getBaseUrl, addCsrfToObject } from './utils.js';
import { Translations } from './translations.js';

export const ZniszczUbranie = (function () {
    let ubranieId = null;
    let selectedButton = null;

    const destroy = async () => {
        const baseUrl = getBaseUrl();

        try {
            const requestData = addCsrfToObject({ id: ubranieId });
            
            const response = await fetch(`${baseUrl}/app/handlers/zniszcz_ubranie.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                credentials: 'same-origin',
                body: JSON.stringify(requestData)
            });

            const data = await response.json();

            if (data.success) {
                selectedButton.disabled = true;
                selectedButton.textContent = Translations.translate('status_changed');
                window.location.reload();
            } else {
                alert(Translations.translate('delete_error'));
            }
        } catch (error) {
            console.error('Error:', error);
            alert(Translations.translate('network_error'));
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
