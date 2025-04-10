<?php
header("Content-Type:text/html; charset=utf-8");

include_once __DIR__ . '/../../app/controllers/PracownikC.php';
include_once __DIR__ . '/../../app/controllers/WydaniaC.php';
include_once __DIR__ . '/../../app/controllers/WydaneUbraniaC.php';

$pracownikC = new PracownikC();
$wydaniaC = new WydaniaC();
$wydaneUbraniaC = new WydaneUbraniaC();

include_once __DIR__ . '/../../layout/header.php';
include_once __DIR__ . '/../../app/database/auth.php';
checkAccess(4);

include_once __DIR__ . '/../../layout/ClassModal.php';
$modal = new ClassModal();
?>
<div id="alertContainer"></div>

<div class="d-flex align-items-center">
    <h2 class="mb-4">Historia wydawania</h2>
    <div id="loadingSpinnerName" class="spinner-border mb-2 mx-4" style="display: none;" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<form action="<?php echo $baseUrl; ?>/historia-wydawania" method="get" autocomplete="off">
    <div class="col-md-5">
        <div class="mb-3">
            <div class="d-flex justify-content-between">
                <label for="id_pracownika" class="form-label">Pracownik:</label>
                <div id="loadingSpinner" class="spinner-border mb-2" style="display: none;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="mb-3 position-relative inputcontainer">
                <input type="text" class="form-control" maxlength="30" placeholder="Imię i nazwisko" id="username" required>
                <input type="hidden" id="pracownikID" name="pracownikID" value="" />
                <ul id="suggestions" class="list-group position-absolute" style="display: none; z-index: 1000; width: 100%; top: 100%;"></ul>
            </div>
        </div>
        <br />
        <button type="submit" class="btn btn-secondary submitBtn mb-4 p-3">Wyświetl</button>
    </div>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['pracownikID']) && !empty($_GET['pracownikID'])) {
    $pracownikID = $_GET['pracownikID'];
    $pracownik = $pracownikC->getById($pracownikID);

    if ($pracownik) {
        $imie = $pracownik['imie'];
        $nazwisko = $pracownik['nazwisko'];

        $historia = $wydaniaC->getWydaniaByPracownikId($pracownikID);

        if ($historia) {
            echo "<h2>Historia wydań dla: $imie $nazwisko</h2> <br/>";
            echo '<table id="example" class="table table-hover table-striped table-bordered text-center align-middle" style="width:100%">';
            echo '<thead class="table-dark"><tr>
            <th scope="col">Data Wydania</th>
            <th scope="col">Nazwa ubrania</th>
            <th scope="col">Rozmiar</th>
            <th scope="col">Ilość</th>
            <th scope="col">Wydane przez</th>
            <th scope="col">Anuluj wydanie</th>
            <th scope="col">Status wydania</th>
            <th scope="col">Zmiana statusu</th>
        </tr></thead>
        <tbody>';

            foreach ($historia as $wydanie) {
                $id_wydania = $wydanie['id_wydania'];
                $data_wydania = $wydanie['data_wydania'];
                $wydane_przez = $wydanie['user_name'];
                $ubrania = $wydaneUbraniaC->getUbraniaByWydanieId($id_wydania);

                $oneMonthAfter = date('Y-m-d', strtotime($data_wydania . ' +1 month'));
                $currentDate = date('Y-m-d');

                foreach ($ubrania as $ubranie) {
                    $nazwa_ubrania = $ubranie['nazwa_ubrania'];
                    $nazwa_rozmiaru = $ubranie['nazwa_rozmiaru'];
                    $ilosc = $ubranie['ilosc'];
                    $status = $ubranie['status'];
                    $data_waznosci = date('Y-m-d', strtotime($ubranie['data_waznosci']));
                    $canBeReported = $ubranie['canBeReported'] == 1;

                    $statusText = $status == 1 ? "Wydane" : ($status == 0 ? "Usunięte z raportu" : ($status == 3 ? "Anulowane" : "Zniszczone ubranie: {$data_waznosci}"));
                    $cancelBtn = $status == 3 ? "Anulowano" : "Anuluj wydanie";
                    $withinOneMonth = ($status == 1 && $currentDate <= $oneMonthAfter);
                    $disabledBtn = !$withinOneMonth ? "disabled" : "";
                    $rowClass = $status == 0 ? "table-warning" : ($status == 2 ? "table-danger" : "");
                    $buttonText = $status == 1 ? "Usuń z raportu" : "Dodaj do raportu";
                    $buttonAction = $status == 1 ? "nieaktywne" : "aktywne";
                    $reportDisabledBtn = !$canBeReported || $status == 2 ? "disabled" : "";
                    $destroyDisabled = $status != 1 ? "disabled" : "";
                    $buttonHtml = "<button class='btn btn-warning cancel-btn' data-id='{$ubranie['id']}' {$disabledBtn}>{$cancelBtn}</button>";

                    echo "<tr class='{$rowClass}'>";
                    echo "<td>" . date('Y-m-d H:i', strtotime($data_wydania)) . "</td>";
                    echo "<td>{$nazwa_ubrania}</td>";
                    echo "<td>{$nazwa_rozmiaru}</td>";
                    echo "<td>{$ilosc}</td>";
                    echo "<td>{$wydane_przez}</td>";
                    if ($disabledBtn) {
                        echo "<td>
                        <span class='d-inline-block' tabindex='0' data-bs-toggle='tooltip' data-bs-placement='top' title='Upłynął czas na anulowanie wydania'>
                            {$buttonHtml}
                        </span>
                      </td>";
                    } else {
                        echo "<td>{$buttonHtml}</td>";
                    }
                    echo "<td>{$statusText}</td>";
                    echo "<td>
                    <div class='d-flex flex-column align-items-center'>
                        <button class='btn btn-secondary inform-btn mb-2' data-id='{$ubranie['id']}' data-action='{$buttonAction}' {$reportDisabledBtn}>{$buttonText}</button>
                        <button class='btn btn-danger destroy-btn' data-id='{$ubranie['id']}' {$destroyDisabled}>Zniszcz ubranie</button>
                    </div>
                  </td>";
                    echo "</tr>";
                }
            }

            echo '</tbody></table>';
        } else {
            echo "<p>Brak historii dla wybranego użytkownika.</p>";
        }
    } else {
        echo "<p>Nie znaleziono pracownika o podanym ID.</p>";
    }
}

$modal->anulujModal();
$modal->zniszczoneModal();

include_once __DIR__ . '/../../layout/footer.php';
?>
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
            infoFiltered: "(filtrowanie spośród _MAX_ dostępnych pozycji)",
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

<script type="module" src="<?php echo $baseUrl; ?>/App.js"></script>