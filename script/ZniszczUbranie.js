import { getBaseUrl } from './GetBaseUrl.js';

export const ZniszczUbranie = (function () {
    let ubranieId = null;
    let selectedButton = null;

    const initialize = function () {
        const informButtons = document.querySelectorAll('.destroy-btn');

        informButtons.forEach(button => {
            button.addEventListener('click', function () {
                ubranieId = this.getAttribute('data-id');
                selectedButton = this;

                $('#confirmDestroyModal').modal('show');
            });
        });
 
        document.getElementById('confirmDestroyBtn').addEventListener('click', function () {
            destroy(); 
            $('#confirmDestroyModal').modal('hide');
        });
        
    };

    const destroy = function () {
        const baseUrl = getBaseUrl();
        
        fetch(`${baseUrl}/handlers/zniszcz_ubranie.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ id: ubranieId  })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                selectedButton.disabled = true;
                selectedButton.textContent = "Status zmieniony";
                window.location.reload();
            } else {
                alert('Błąd podczas usuwania zniszczonego ubrania.');
            }
        })
        .catch(error => {
            console.error('Błąd:', error);
            alert('Wystąpił błąd podczas przesyłania żądania.');
        });
    };

    return {
        initialize
    };
})();
