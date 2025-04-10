export const ModalEdytujPracownika = (function () {
    let pracownikId = null;

    const initialize = function () {
        $('#example').on('click', '.open-modal-btn', function () {
            pracownikId = $(this).data('id'); 
            const index = $(this).data('index'); 

            const pracownicyData = document.getElementById("pracownicy-data");
            if (pracownicyData) {
                const pracownicy = JSON.parse(pracownicyData.textContent);
                const pracownik = pracownicy[index];

                document.getElementById("pracownik_id").value = pracownik.id_pracownik;
                document.getElementById("imie").value = pracownik.imie;
                document.getElementById("nazwisko").value = pracownik.nazwisko;
                document.getElementById("stanowisko").value = pracownik.stanowisko;
                document.getElementById("status").value = pracownik.status;

                $('#editModal').modal('show');
            }
        });
    };

    return {
        initialize
    };
})();
