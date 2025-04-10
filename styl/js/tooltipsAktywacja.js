/* 
 * Wszelkie prawa zastrzeżone.
 * Kopiowanie, edytowanie, udostępnianie bez zgody autora zabronione!
 */


$(document).ready(function () {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
});

