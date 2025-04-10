export const RedirectStatus = (function () {
    const initialize = function () {
        const informButtons = document.querySelectorAll('.redirect-btn');

        informButtons.forEach(button => {
            button.addEventListener('click', function () {
                const pracownikId = this.getAttribute('data-pracownik-id');
                const pracownikImie = this.getAttribute('data-pracownik-imie');
                const pracownikNazwisko = this.getAttribute('data-pracownik-nazwisko');
                const pracownikStanowisko = this.getAttribute('data-pracownik-stanowisko');

                // Get the base URL from a meta tag or compute it
                const baseUrl = document.querySelector('meta[name="base-url"]')?.getAttribute('content') || '';
                
                // Use the clean URL structure
                window.location.href = `${baseUrl}/wydaj-ubranie?pracownikId=${pracownikId}&imie=${pracownikImie}&nazwisko=${pracownikNazwisko}&stanowisko=${pracownikStanowisko}&fromRaport=1`;
            });
        });
    };

    return {
        initialize
    };
})();

