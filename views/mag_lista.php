<?php
header("Content-Type:text/html; charset=utf-8");

include_once __DIR__ . '../../layout/header.php';
include_once __DIR__ . '../../app/auth/Auth.php';
checkAccess(2);
include_once __DIR__ . '../../app/services/ServiceContainer.php';

$serviceContainer = ServiceContainer::getInstance();
$stanMagazynuC = $serviceContainer->getController('StanMagazynuC');
$ubrania = $stanMagazynuC->readAll();
?>

<div id="alertContainer"></div>

<h2 class="mb-4">Ubrania</h2>
<table id="example" class="table table-striped table-bordered display text-center align-middle" style="width:100%">
    <thead class="table-dark">
        <tr>
            <th scope="col">Nazwa</th>
            <th scope="col">Rozmiar</th>
            <th scope="col">Ilość</th>
            <th scope="col">Ilość min</th>
            <th scope="col">Zamówić</th>
            <th scope="col">Edycja</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($ubrania as $ubranie) {
            $ile = $ubranie['ilosc'];
            $ileMin = $ubranie['iloscMin'];
            echo '<tr><td>' . htmlspecialchars($ubranie['nazwa_ubrania']) . '</td>'
                . '<td>' . htmlspecialchars($ubranie['nazwa_rozmiaru']) . '</td>'
                . '<td>' . $ile . '</td>'
                . '<td>' . $ileMin . '</td>'
                . ($ile >= $ileMin ? '<td>NIE</td>' : '<td class="table-danger">ZAMÓW!</td>')
                . '<td><button class="btn btn-secondary open-modal-btn" 
                                    data-id="' . $ubranie['id'] . '" 
                                    >Edycja</button></td>'
                . '</tr>';
        }

        ?>
    </tbody>
</table>

<div id="editModal" class="modal fade" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content container">
            <div class="modal-header">
                <h2 class="modal-title" id="editModalLabel">Edytuj</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edycjaUbraniaForm" action="test" method="post" class="needs-validation" novalidate>
                    <input type="hidden" id="id_ubrania" name="id">
                    <div class="mb-3 mt-2">
                        <label for="productName" class="form-label">Nazwa produktu:</label>
                        <input type="text" class="form-control" id="productName" name="nazwa" required>
                    </div>
                    <div class="mb-3">
                        <label for="sizeName" class="form-label">Rozmiar:</label>
                        <input type="text" class="form-control" id="sizeName" name="rozmiar" required>
                    </div>
                    <div class="mb-3">
                        <label for="ilosc" class="form-label">Obecna ilość:</label>
                        <input type="number" class="form-control" id="ilosc" name="ilosc" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label for="iloscMin" class="form-label">Ilość minimalna:</label>
                        <input type="number" class="form-control" id="iloscMin" name="iloscMin" min="1" required>
                    </div>
                    <div class="mb-5">
                        <label for="id_uwagi" class="form-label">Uwagi:</label>
                        <textarea id="id_uwagi" name="uwagi" rows="4" cols="50" class="form-control" spellcheck="false"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                <button type="submit" form="edycjaUbraniaForm" id="zapiszUbranie" class="btn btn-primary">Zapisz</button>
            </div>
        </div>
    </div>
</div>


<script id="ubrania-data" type="application/json"><?php echo json_encode($ubrania); ?></script>
<script type="module" src="<?php echo $baseUrl; ?>/App.js"></script>
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


<?php include_once __DIR__ . '../../layout/footer.php'; ?>