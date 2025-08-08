<?php
header("Content-Type:text/html; charset=utf-8");

include_once __DIR__ . '../../layout/header.php';
include_once __DIR__ . '../../app/auth/Auth.php';
checkAccess(4);

include_once __DIR__ . '../../app/controllers/PracownikC.php';
include_once __DIR__ . '../../app/controllers/WydaniaC.php';
include_once __DIR__ . '../../app/controllers/WydaneUbraniaC.php';

$pracownikC = new PracownikC();
$wydaniaC = new WydaniaC();
$wydaneUbraniaC = new WydaneUbraniaC();
$ubraniaPoTerminie = $wydaneUbraniaC->getUbraniaPoTerminie();
?>
<div id="alertContainer"></div>

<h2 class="mb-4">Raport wydawania</h2>
<table id="example" class="table table-striped table-hover table-bordered text-center align-middle" style="width:100%">
    <thead class="table-dark">
        <tr>
            <th scope="col">Data ważności</th>
            <th scope="col">Imie i nazwisko</th>
            <th scope="col">Stanowisko</th>
            <th scope="col">Ubranie</th>
            <th scope="col">Rozmiar</th>
            <th scope="col">Ilość</th>
            <th scope="col">Status</th>
            <th scope="col">Akcje</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $wydania = $wydaniaC->getAllWydania();
        if ($wydania) {
            foreach ($wydania as $wydanie) {
                $id_wydania = $wydanie['id_wydania'];
                $pracownikImie = $wydanie['imie'];
                $pracownikNazwisko = $wydanie['nazwisko'];
                $pracownikStanowisko = $wydanie['stanowisko'];
                $ubrania = $wydaneUbraniaC->getUbraniaByWydanieIdTermin($id_wydania);

                foreach ($ubrania as $ubranie) {
                    $rowClass = $ubranie['statusText'] === 'Przeterminowane' ? 'table-danger' : ($ubranie['statusText'] === 'Koniec ważności' ? 'table-warning' : '');

                    echo "<tr class='{$rowClass}'>";
                    echo "<td>" . date('Y-m-d H:i', strtotime($ubranie['data_waznosci'])) . "</td>";
                    
                    echo "<td>{$pracownikImie} {$pracownikNazwisko}</td>";
                    echo "<td>{$pracownikStanowisko}</td>";
                    echo "<td>{$ubranie['nazwa_ubrania']}</td>";
                    echo "<td>{$ubranie['nazwa_rozmiaru']}</td>";
                    echo "<td>{$ubranie['ilosc']}</td>";
                    echo "<td>{$ubranie['statusText']}</td>";
                    echo "<td>
                    <div class='d-flex justify-content-between'>
                        <button class='btn btn-primary redirect-btn me-2' 
                                    data-pracownik-id='{$wydanie['pracownik_id']}' 
                                    data-pracownik-imie='{$pracownikImie}' 
                                    data-pracownik-nazwisko='{$pracownikNazwisko}' 
                                    data-pracownik-stanowisko='{$pracownikStanowisko}'>Wydaj</button>
                        <button class='btn btn-secondary inform-btn p-1' data-raport='true' data-id='{$ubranie['id']}'>Usuń z raportu</button>
                     </div>
                        </td>
                    </tr>";
                }
            }
        } else {
            echo "<tr><td colspan='8'>Brak wydanych ubrań.</td></tr>";
        }
        ?>
    </tbody>
</table>

<br />

<h2 class="mb-4 mt-3">Wydane ubrania</h2>
<table id="example1" class="table table-striped table-bordered display text-center align-middle" style="width:100%">
    <thead class="table-dark">
        <tr>
            <th scope="col">Nazwa ubrania</th>
            <th scope="col">Rozmiar</th>
            <th scope="col">Ilość wydana</th>
            <th scope="col">Ilość w magazynie</th>
            <th scope="col">Ilość minimalna</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($ubraniaPoTerminie)) : ?>
            <?php foreach ($ubraniaPoTerminie as $ubranie) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($ubranie['nazwa_ubrania']); ?></td>
                    <td><?php echo htmlspecialchars($ubranie['nazwa_rozmiaru']); ?></td>
                    <td><?php echo htmlspecialchars($ubranie['ilosc']); ?></td>
                    <td><?php echo htmlspecialchars($ubranie['ilosc_magazyn']); ?></td>
                    <td><?php echo htmlspecialchars($ubranie['ilosc_min']); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="5">Brak.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include_once __DIR__ . '../../layout/footer.php'; ?>
<script>
    function initializeDataTable(tableId) {
        new DataTable(tableId, {
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
                infoFiltered: "(filtrowanie spośród _MAX_ dostępnych pozycji)",
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
    }

    initializeDataTable('#example');
    initializeDataTable('#example1');
</script>
<script type="module" src="<?php echo $baseUrl; ?>/App.js"></script>