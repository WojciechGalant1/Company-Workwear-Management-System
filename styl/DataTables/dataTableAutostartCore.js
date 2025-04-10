/* 
 * Wszelkie prawa zastrzeżone.
 * Kopiowanie, edytowanie, udostępnianie bez zgody autora zabronione!
 * Click nbfs://nbhost/SystemFileSystem/Templates/Other/javascript.js to edit this template
 */

//import 'datatables.js';


function zrobDataTables(nazwaTab, sortowanie='') {
    if(sortowanie===''){
        sort = [[0, "asc"], [1, "asc"]];
    }else{
        //czy tablica?
        sort=sortowanie;
    }

    let nazwa = '#' + nazwaTab;
    $(nazwa).DataTable({
        "lengthMenu": [[15, 25, 50, -1], [15, 25, 50, "Wszystkie"]],
        "language": {
            "processing": "Przetwarzanie...",
            "search": "Szukaj:",
            "lengthMenu": "Pokaż _MENU_ pozycji",
            "info": "Pozycje od _START_ do _END_ z _TOTAL_ łącznie",
            "infoEmpty": "Pozycji 0 z 0 dostępnych",
            "infoFiltered": "(filtrowanie spośród _MAX_ dostępnych pozycji)",
            "infoPostFix": "",
            "loadingRecords": "Wczytywanie...",
            "zeroRecords": "Nie znaleziono pasujących pozycji",
            "emptyTable": "Brak danych",
            "paginate": {
                "first": "Pierwsza",
                "previous": "Poprzednia",
                "next": "Następna",
                "last": "Ostatnia"
            }
        },
        "dom": 'lBfrtip',
        "buttons": [],
        "order": sort
    });

}//k. funkcji








