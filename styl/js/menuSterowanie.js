/*
 * Wszelkie prawa zastrzeżone. 
 * Kopiowanie, edytowanie, udostępnianie bez zgody autora zabronione !
 *
 * @author Krzysztof Wielgosz / Dugi / kawu07@gmail.com
 */

var menuShow = 1;
document.addEventListener("DOMContentLoaded", function (event) {

    document.querySelectorAll('.sidebar .nav_link').forEach(function (element) {
        element.addEventListener('click', function (e) {
            let nextEl = element.nextElementSibling;
            let parentEl = element.parentElement;

            if (nextEl) {   //ma dzieci
                //jeżeli nie ma kalsy sub czyli dziecka to zatrzymaj
                if (!nextEl.classList.contains('sub')) {
                    e.preventDefault(); //zatrzymaj przekierowanie i pozwól rozwinąć menu
                }


                let mycollapse = new bootstrap.Collapse(nextEl);

                if (nextEl.classList.contains('showMenu')) {
                    mycollapse.hide();
                } else {
                    mycollapse.show();
                    // find other submenus with class=show
                    var opened_submenu = parentEl.parentElement.querySelector('.submenu.show');
                    // if it exists, then close all of them
                    if (opened_submenu) {
                        
                        new bootstrap.Collapse(opened_submenu);//collapse(ukrywa elementy)
                    }
                }
            }
        });
    });

    const showNavbar = (toggleId, navId, bodyId, headerId, show) => {
        const mOpcja = document.getElementById(toggleId),
                nav = document.getElementById(navId),
                bodypd = document.getElementById(bodyId),
                headerpd = document.getElementById(headerId);

        //funkcja zwija/rozwija lewe menu
        function flipMenu() {
            // show navbar
            nav.classList.toggle('showMenu');
            // change icon
            //nie działa, bo jest dwie klasy???
            //mOpcja.classList.toggle('bi-list');
            if (menuShow === 0) {
                mOpcja.classList.remove('bi-list');
                mOpcja.classList.add('bi-x-lg');
            } else {
                mOpcja.classList.remove('bi-x-lg');
                mOpcja.classList.add('bi-list');
            }


            // add padding to body
            bodypd.classList.toggle('body-pd');
            // add padding to header
            headerpd.classList.toggle('body-pd');

            menuShow = (menuShow === 0) ? 1 : 0;
            //console.log('menu Po : ' + menuShow);
        }


        if (show === 1) {
            flipMenu();
        } else {

            // Validate that all variables exist
            if (mOpcja && nav && bodypd && headerpd) {

                mOpcja.addEventListener('click', () => {
                    flipMenu();

                });
            }
        }
    };

    showNavbar('guzikMenu', 'nav-bar', 'body-pd', 'header', '0');

    /*===== LINK ACTIVE =====*/
    const linkColor = document.querySelectorAll('.nav_link');

    function colorLink() {
        if (linkColor) {
            linkColor.forEach(l => l.classList.remove('active'));
            this.classList.add('active');

            if (menuShow === 0) {
                showNavbar('guzikMenu', 'nav-bar', 'body-pd', 'header', 1);
            }
        }
    }
    linkColor.forEach(l => l.addEventListener('click', colorLink));

    // Your code to run since DOM is loaded and ready
});