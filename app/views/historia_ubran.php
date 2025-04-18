<?php
header("Content-Type:text/html; charset=utf-8");

include_once __DIR__ . '/../../layout/header.php';
include_once __DIR__ . '/../../app/database/Auth.php';
checkAccess(5);
include_once __DIR__ . '/../controllers/PracownikC.php';
include_once __DIR__ . '/../controllers/WydaniaC.php';
include_once __DIR__ . '/../controllers/WydaneUbraniaC.php';

$pracownikC = new PracownikC();
$wydaniaC = new WydaniaC();
$wydaneUbraniaC = new WydaneUbraniaC();

$data = $wydaneUbraniaC->getWydaneUbraniaWithDetails();
?>
<div id="alertContainer"></div>

<h2 class="mb-4">Historia ubrań</h2>

<table id="example" class="table table-striped table-bordered display text-center align-middle" style="width:100%">
    <thead class="table-dark">
        <tr>
            <th scope="col">Nazwa ubrania</th>
            <th scope="col">Rozmiar</th>
            <th scope="col">Wydane dla</th>
            <th scope="col">Szczegóły</th>
        </tr>
    </thead>
    <tbody>
        
    <?php foreach ($data as $index => $row) {
    echo '<tr>
        <td>' . htmlspecialchars($row['nazwa_ubrania']) . '</td>'
        . '<td>' . htmlspecialchars($row['rozmiar']) . '</td>'
        . '<td>' . htmlspecialchars($row['wydane_dla']) . '</td>'
        . '<td>
            <button class="btn btn-secondary open-modal-btn" 
                    data-id="' . $row['id'] . '" 
                    data-details="' . htmlspecialchars(json_encode($row)) . '">
                Wyświetl
            </button>
        </td>'
        . '</tr>';
} ?>


</tbody>
</table>

<div id="detailModal" class="modal fade" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content container">
            <div class="modal-header">
                <h2 class="modal-title" id="detailModalLabel">Szczegóły</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
    <!-- 
            nazwa_ubrania
            rozmiar
            ilosc
            wydane_przez
            wydane_dla
            data        
    -->
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zamknij</button>
            </div>
        </div>
    </div>
</div>

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
<?php include_once __DIR__ . '/../../layout/footer.php'; ?>