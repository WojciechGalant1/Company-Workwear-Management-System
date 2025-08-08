<?php
header("Content-Type:text/html; charset=utf-8");

include_once __DIR__ . '../../layout/header.php';
include_once __DIR__ . '../../app/auth/Auth.php';
checkAccess(2);
include_once __DIR__ . '../../app/controllers/HistoriaZamowienC.php';

$zamowieniaC = new HistoriaZamowienC();
$zamowienia = $zamowieniaC->getAll();
?>

    <h2 class="mb-4">Historia zamowień</h2>
    
    <table id="example" class="table table-striped table-bordered display text-center align-middle" style="width:100%">
        <thead class="table-dark">
        <tr>
            <th scope="col">Data</th>
            <th scope="col">Nazwa</th>
            <th scope="col">Rozmiar</th>
            <th scope="col">Firma</th>
            <th scope="col">Dodane przez</th>
            <th scope="col">Ilość</th>
            <th scope="col">Status</th>
            <th scope="col">Uwagi</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($zamowienia as $zamowienie) { ?>
            <tr>
                <td><?php echo date('Y-m-d H:i', strtotime($zamowienie['data_zamowienia'])); ?></td>
                <td><?php echo $zamowienie['nazwa_ubrania']; ?></td>
                <td><?php echo $zamowienie['rozmiar_ubrania']; ?></td>
                <td><?php echo $zamowienie['firma']; ?></td>
                <td><?php echo $zamowienie['nazwa_uzytkownika']; ?></td>
                <td><?php echo $zamowienie['ilosc']; ?></td>
                <td><?php echo $zamowienie['status'] == 1 ? 'Zrealizowane' : ($zamowienie['status'] == 2 ? 'Inwentaryzacja' : '[Brak danych]'); ?></td>
                <td><?php echo $zamowienie['uwagi']; ?></td>
            </tr>
        <?php } ?>
    </tbody>
    </table>
    <script>
        new DataTable('#example', {
            lengthMenu: [
                [15, 25, 50, -1],
                [15, 25, 50, "Wszystkie"],
            ],
            language: {
                processing: "Przetwarzanie...",
                search: "Szukaj:",
                lengthMenu: "Pokaż _MENU_ pozycji",
                info: "Pozycje od _START_ do _END_ z _TOTAL_ łącznie",
                infoEmpty: "Pozycji 0 z 0 dostępnych",
                infoFiltered:
                    "(filtrowanie spośród _MAX_ dostępnych pozycji)",
                infoPostFix: "",
                loadingRecords: "Wczytywanie...",
                zeroRecords: "Nie znaleziono pasujących pozycji",
                emptyTable: "Brak danych",
                paginate: {
                    first: "Pierwsza",
                    previous: "Poprzednia",
                    next: "Następna",
                    last: "Ostatnia",
                },
            }
        });
    </script>

<?php include_once __DIR__ . '../../layout/footer.php'; ?>