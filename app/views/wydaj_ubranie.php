<?php

include_once __DIR__ . '/../../layout/header.php';
include_once __DIR__ . '/../../app/database/auth.php';
checkAccess(1);

include_once __DIR__ . '/../../app/controllers/PracownikC.php';
include_once __DIR__ . '/../../app/controllers/UbranieC.php';

include_once __DIR__ . '/../../app/controllers/WydaniaC.php';
include_once __DIR__ . '/../../app/controllers/WydaneUbraniaC.php';


$pracownikC = new PracownikC();
$ubranieC = new UbranieC();
$ubrania = $ubranieC->getAllUnique();

include_once __DIR__ . '/../../app/helpers/DateHelper.php';

$miesiace = [6, 12, 18, 24];

$fromRaport = isset($_GET['fromRaport']) && $_GET['fromRaport'] == '1';
$imie = '';
$nazwisko = '';
$stanowisko = '';

if ($fromRaport) {
    $pracownikId = isset($_GET['pracownikId']) ? htmlspecialchars($_GET['pracownikId']) : '';
    $imie = isset($_GET['imie']) ? htmlspecialchars($_GET['imie']) : '';
    $nazwisko = isset($_GET['nazwisko']) ? htmlspecialchars($_GET['nazwisko']) : '';
    $stanowisko = isset($_GET['stanowisko']) ? htmlspecialchars($_GET['stanowisko']) : '';


    $wydaneUbraniaC = new WydaneUbraniaC();

    $pracownikId = isset($_GET['pracownikId']) ? htmlspecialchars($_GET['pracownikId']) : '';
    $expiredUbrania = [];

    if ($pracownikId) {
        $wydaniaC = new WydaniaC();
        $wydaniaPracownika = $wydaniaC->getWydaniaByPracownikId($pracownikId);

        foreach ($wydaniaPracownika as $wydanie) {
            $expiringUbrania = $wydaneUbraniaC->getUbraniaByWydanieIdTermin($wydanie['id_wydania']);
            foreach ($expiringUbrania as $ubranie) {
                $expiredUbrania[] = $ubranie;
            }
        }
    }
}


?>

<div id="alertContainer"></div>

<div class="d-flex align-items-center">
    <h2 class="mb-4">Wydawanie ubrań</h2>
    <div id="loadingSpinnerName" class="spinner-border mb-2 mx-4" style="display: none;" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<form id="wydajUbranieForm" action="<?php echo $baseUrl; ?>/app/forms/wydaj_ubranieF.php" method="post" autocomplete="off">
    <div class="mb-3 col-md-6">
        <div class="d-flex justify-content-between">
            <label for="username" class="form-label">Pracownik:</label>
        </div>
        <div class="mb-3 position-relative inputcontainer">
            <input type="text" class="form-control" maxlength="30" placeholder="Imię i nazwisko" id="username"
                value="<?php echo trim("$imie $nazwisko $stanowisko") !== '' ? "$imie $nazwisko ($stanowisko)" : ''; ?>" required>
            <input type="hidden" id="pracownikID" name="pracownikID" value="<?php echo $pracownikId; ?>" />
            <ul id="suggestions" class="list-group position-absolute" style="display: none; z-index: 1000; width: 100%; top: 100%;"></ul>
        </div>
    </div>
    <div id="ubraniaContainer" class="p-3 bg-body rounded">
        <div class="border border-2 p-3 row ubranieRow mt-3 mb-3 bg-body rounded">
            <div class="mb-3 col-md-11">
                <div class="form-check form-check-inline border-bottom border-3">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                    <label class="form-check-label" for="inlineRadio1">Nazwa ubrania i rozmiar</label>
                </div>
                <div class="form-check form-check-inline border-bottom border-primary border-3">
                    <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2" checked>
                    <label class="form-check-label" for="inlineRadio2">Kod</label>
                </div>
            </div>
            <div class="col-md-2 nazwaSection" style="display: none;">
                <label for="id_ubrania" class="form-label">Ubranie:</label>
                <select id="id_ubrania" name="ubrania[0][id_ubrania]" class="form-select ubranie-select" data-live-search="true" disabled>
                    <option value="">Wybierz ubranie</option>
                    <?php foreach ($ubrania as $ubranie) { ?>
                        <option value="<?php echo $ubranie['id']; ?>">
                            <?php echo $ubranie['nazwa']; ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-2 rozmiarSection" style="display: none;">
                <label for="id_rozmiar" class="form-label">Rozmiar:</label>
                <select id="id_rozmiar" name="ubrania[0][id_rozmiar]" class="form-select rozmiar-select" data-live-search="true" disabled>
                    <option value="">Wybierz rozmiar</option>
                </select>
            </div>
            <div class="col-md-4 kodSection" style="display: block;">
                <label for="kod" class="form-label">Kod:</label>
                <input type="text" class="form-control kod-input" id="kod" name="ubrania[0][kod]">
                <input type="hidden" id="id_ubrania" name="ubrania[0][id_ubrania]" value="" />
                <input type="hidden" id="id_rozmiar" name="ubrania[0][id_rozmiar]" value="" />
            </div>
            <div class="col-md-2">
                <label for="ilosc" class="form-label">Ilość:</label>
                <input type="number" class="form-control" min="1" value="1" id="ilosc" name="ubrania[0][ilosc]" required>
            </div>
            <div class="col-md-3">
                <label for="data_waznosci" class="form-label">Data ważności:</label>
                <select id="data_waznosci" name="ubrania[0][data_waznosci]" class="form-select data_w-select" required>
                <?php foreach ($miesiace as $miesiac): ?>
                        <option value="<?= $miesiac; ?>">
                            <?= $miesiac; ?> miesięcy (<?= nowaData($miesiac); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end justify-content-between">
                <button type="button" class="btn btn-success addUbranieBtn "><i class="bi bi-plus-lg"></i> Dodaj ubranie</button>
                <button type="button" class="btn btn-danger removeUbranieBtn ms-2" style="display: none;"><i class="bi bi-x-lg"></i> Usuń ubranie</button>
            </div>
        </div>
    </div>
    <div class="mb-5 col-md-6">
        <label for="id_uwagi" class="form-label">Uwagi:</label>
        <textarea id="id_uwagi" name="uwagi" rows="4" cols="50" class="form-control"></textarea>
    </div>
    <div class="d-flex align-items-center mt-3 mb-3">
        <button type="submit" class="btn btn-primary submitBtn mb-3 p-3">Wydaj ubrania</button>
        <div id="loadingSpinner" class="spinner-border mb-2 mx-4" style="display: none;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</form>

<!-- modal-->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">Zmiana statusu ubrań po terminie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Ubranie</th>
                            <th scope="col">Rozmiar</th>
                            <th scope="col">Ilość</th>
                            <th scope="col">Data Ważności</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($expiredUbrania)) : ?>
                            <?php foreach ($expiredUbrania as $ubranie) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($ubranie['nazwa_ubrania']); ?></td>
                                    <td><?php echo htmlspecialchars($ubranie['nazwa_rozmiaru']); ?></td>
                                    <td><?php echo htmlspecialchars($ubranie['ilosc']); ?></td>
                                    <td><?php echo date('Y-m-d H:i', strtotime($ubranie['data_waznosci'])); ?></td>
                                    <td>
                                        <button class="btn btn-secondary inform-btn"
                                            data-id="<?php echo htmlspecialchars($ubranie['id']); ?>"
                                            id="statusBtn-<?php echo $ubranie['id']; ?>">Usuń z raportu</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" id="confirmButton" class="btn btn-primary">Zamknij</button>
            </div>
        </div>
    </div>
</div>

<script>
    window.fromRaport = <?php echo json_encode($fromRaport); ?>;
</script>

<script type="module" src="<?php echo $baseUrl; ?>/App.js"></script>
<?php include_once __DIR__ . '/../../layout/footer.php'; ?>