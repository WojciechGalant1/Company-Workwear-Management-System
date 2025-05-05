export const HistoriaUbranSzczegoly = (() => {
    const initialize = () => {
        $('#example').on('click', '.open-modal-btn', (event) => {
            const clickedBtn = event.currentTarget;
            const details = $(clickedBtn).data('details');

            console.log('Details:', details);

            if (!details) {
                console.error('Brak danych w "data-details"');
                return;
            }

            $('#detailModal .modal-body').html(`
                <p><strong>Nazwa ubrania:</strong> ${details.nazwa_ubrania}</p>
                <p><strong>Rozmiar:</strong> ${details.rozmiar}</p>
                <p><strong>Ilość:</strong> ${details.ilosc}</p>
                <p><strong>Wydane przez:</strong> ${details.wydane_przez}</p>
                <p><strong>Wydane dla:</strong> ${details.wydane_dla}</p>
                <p><strong>Data:</strong> ${details.data}</p>
            `);

            $('#detailModal').modal('show');
        });
    };

    return { initialize };
})();
