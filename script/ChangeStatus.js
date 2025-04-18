import { GetBaseUrl } from './GetBaseUrl.js';

export const ChangeStatus = (function () {
    let selectedId = null;
    let selectedButton = null;

    const initialize = function () {
        const informButtons = document.querySelectorAll('.inform-btn');

        informButtons.forEach(button => {
            button.addEventListener('click', function () {
                selectedId = this.getAttribute('data-id');
                selectedButton = this;

                if (window.location.pathname.includes('historia.php')) {
                    const currentAction = this.getAttribute('data-action');
                    const currentStatus = currentAction === 'nieaktywne' ? 1 : 0;
                    updateStatus(currentStatus);
                } else {
                    updateStatusForModal();
                }

            });
        });
    };

    const updateStatus = async function (currentStatus) {
        const baseUrl = GetBaseUrl();

        try {
            const response = await fetch(`${baseUrl}/handlers/changeStatus.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: selectedId, currentStatus: currentStatus })
            });

            const data = await response.json();

            if (data.success) {
                const newStatus = data.newStatus;
                if (newStatus === 1) {
                    selectedButton.disabled = true;
                    selectedButton.textContent = "Usuń z raportu";
                    selectedButton.setAttribute("data-action", "nieaktywne");
                } else {
                    selectedButton.disabled = true;
                    selectedButton.textContent = "Dodaj do raportu";
                    selectedButton.setAttribute("data-action", "aktywne");
                }
                window.location.reload();
            } else {
                alert('Błąd podczas aktualizacji statusu.');
            }    
        } catch (error) {
            console.error('Błąd:', error);
            alert('Wystąpił błąd podczas aktualizacji statusu.');
        }

};

    const updateStatusForModal = async function () {
        const isRaport = selectedButton.getAttribute('data-raport') === 'true';
        const baseUrl = GetBaseUrl();

        try {
            const response = await fetch(`${baseUrl}/handlers/changeStatus.php`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ id: selectedId, currentStatus: 1 })
            });

            const data = await response.json();

            if (data.success) {
                selectedButton.disabled = true;
                selectedButton.textContent = "Usunięto z raportu";
                if (isRaport) {
                    window.location.reload();
                }
            } else {
                alert('Błąd podczas aktualizacji statusu.');
            }

        } catch (error) {
            console.error('Błąd:', error);
                alert('Wystąpił błąd podczas aktualizacji statusu.');
        }
    };

    return {
        initialize
    };
})();
