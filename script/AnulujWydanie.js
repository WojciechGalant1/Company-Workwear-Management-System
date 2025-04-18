import { GetBaseUrl } from './GetBaseUrl.js';

export const AnulujWydanie = (function () {
    let ubranieId = null;
    let selectedButton = null;

    const initialize = function () {
        const informButtons = document.querySelectorAll('.cancel-btn');

        informButtons.forEach(button => {
            button.addEventListener('click', function () {
                ubranieId = this.getAttribute('data-id');
                selectedButton = this;

                $('#confirmCancelModal').modal('show');
            });
        });

        document.getElementById('confirmCancelBtn').addEventListener('click', function () {
            cancel(); 
            $('#confirmCancelModal').modal('hide');
        });
    };

    const cancel = async function () {
        const baseUrl = GetBaseUrl();
        
        try { 
            const response = await fetch(`${baseUrl}/handlers/anuluj_wydanie.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: ubranieId  })
            });
            
            const data = await response.json();

            if (data.success) {
                selectedButton.disabled = true;
                selectedButton.textContent = "Anulowano";
                window.location.reload();
            } else {
                alert('Błąd podczas anulowania wydania.');
            }

        } catch (error) {
            console.error('Błąd:', error);
            alert('Wystąpił błąd podczas anulowania wydania.');

        }
        
    };

    return {
        initialize
    };
})();
