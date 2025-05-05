import { GetBaseUrl } from './GetBaseUrl.js';

export const AnulujWydanie = (function () {
    let ubranieId = null;
    let selectedButton = null;

    const initialize = () => {
        const informButtons = document.querySelectorAll('.cancel-btn');
    
        informButtons.forEach(button => {
            button.addEventListener('click', (event) => {
                const clickedButton = event.currentTarget;
    
                ubranieId = clickedButton.getAttribute('data-id');
                selectedButton = clickedButton;
    
                $('#confirmCancelModal').modal('show');
            });
        });
    
        document.getElementById('confirmCancelBtn')
            .addEventListener('click', () => {
                cancel();
                $('#confirmCancelModal').modal('hide');
            });
    };
    

    const cancel = async () => {
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

    return { initialize };
})();

