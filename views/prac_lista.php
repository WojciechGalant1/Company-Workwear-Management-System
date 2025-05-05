<?php
header("Content-Type:text/html; charset=utf-8");

include_once __DIR__ . '../../layout/header.php';
include_once __DIR__ . '../../app/database/Auth.php';
checkAccess(4);
include_once __DIR__ . '../../app/controllers/PracownikC.php';

$pracownikC = new PracownikC();
$pracownicy = $pracownikC->getAll();
?>

<div id="alertContainer"></div>

<h2 class="mb-4">Pracownicy</h2>
<table id="example" class="table table-striped table-bordered display text-center align-middle" style="width:100%">
    <thead class="table-dark">
    <tr>
        <th scope="col">Imię</th>
        <th scope="col">Nazwisko</th>
        <th scope="col">Stanowisko</th>
        <th scope="col">Status</th>
        <th scope="col">Edycja</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($pracownicy as $index => $pracownik) { ?>
        <tr>
            <td><?php echo $pracownik['imie']; ?></td>
            <td><?php echo $pracownik['nazwisko']; ?></td>
            <td><?php echo $pracownik['stanowisko']; ?></td>
            <td><?php echo $pracownik['status'] == 1 ? 'Aktywny' : '[brak danych]'; ?></td>
            <td class="text-center">
                <button class="btn btn-secondary open-modal-btn" data-index="<?php echo $index; ?>"
                        data-id="<?php echo $pracownik['id_pracownik']; ?>">Edytuj
                </button>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<script id="pracownicy-data" type="application/json"><?php echo json_encode($pracownicy); ?></script>

<!-- Modal -->
<div id="editModal" class="modal fade" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-md">
        <div class="modal-content container">
            <div class="modal-header">
                <h2 class="modal-title" id="editModalLabel">Edytuj pracownika</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edycjaPracownikaForm" action="<?php echo $baseUrl; ?>/app/forms/prac_listaF.php" method="post" class="needs-validation" novalidate>
                    <input type="hidden" id="pracownik_id" name="id">
                    <div class="mb-3 mt-2">
                        <label for="imie" class="form-label">Imię:</label>
                        <input type="text" class="form-control" id="imie" name="imie" required>
                        <div class="invalid-feedback">
                            Pole imię jest wymagane.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="nazwisko" class="form-label">Nazwisko:</label>
                        <input type="text" class="form-control" id="nazwisko" name="nazwisko" required>
                        <div class="invalid-feedback">
                            Pole nazwisko jest wymagane.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="stanowisko" class="form-label">Stanowisko:</label>
                        <input type="text" class="form-control" id="stanowisko" name="stanowisko" required>
                        <div class="invalid-feedback">
                            Pole stanowisko jest wymagane.
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status:</label>
                        <select id="status" name="status" class="form-select data_w-select" required>
                            <option value="1">Aktywny</option>
                            <option value="0">Nieaktywny</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                <button type="submit" form="edycjaPracownikaForm" id="zapiszPracownika" class="btn btn-primary">Zapisz</button>
            </div>
        </div>
    </div>
</div>


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
