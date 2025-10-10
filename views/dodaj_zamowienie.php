<?php 
include_once __DIR__ . '../../layout/header.php';
include_once __DIR__ . '../../app/auth/Auth.php';
checkAccess(2);
?>

<div id="alertContainer"></div>

<h2 class="mb-4">Dodawanie zamówienia</h2>

<form id="zamowienieForm" action="<?php echo $baseUrl; ?>/app/forms/dodaj_zamowienieF.php" method="post" class="needs-validation" autocomplete="off">
    <?php echo CsrfHelper::getTokenField(); ?>
    <div class="mb-3 p-3" id="ubraniaContainer">
        <div class="row ubranieRow mt-3 mb-3 border border-2 p-3 bg-body rounded">
            <div class="col-md-3 ">
                <label for="kod" class="form-label">Kod:</label>
                <input type="text" class="form-control" id="kod" name="ubrania[0][kod]" required>
            </div>
            <div class="col-md-2 position-relative">
                <label for="productName" class="form-label">Nazwa produktu:</label>
                <div class="position-relative inputcontainer">
                    <input type="text" class="form-control" id="productName" name="ubrania[0][nazwa]" value="" required>
                    <ul id="productSuggestions" class="productSuggestions list-group position-absolute" style="display: none; z-index: 1000; width: 100%; top: 100%;"></ul>
                </div>
            </div>
            <div class="col-md-2 position-relative">
                <label for="sizeName" class="form-label">Rozmiar:</label>
                <div class="position-relative inputcontainer">
                    <input type="text" class="form-control" id="sizeName" name="ubrania[0][rozmiar]" value="" required>
                    <ul id="sizeSuggestions" class="sizeSuggestions list-group position-absolute" style="display: none; z-index: 1000; width: 100%; top: 100%;"></ul>
                </div>
            </div>
            <div class="col-md-2">
                <label for="ilosc" class="form-label">Ilość:</label>
                <input type="number" class="form-control" name="ubrania[0][ilosc]" min="1" value="1" required>
            </div>
            <div class="col-md-2" style="display: block;">
                <label for="iloscMin" class="form-label">Ilość minimalna:</label>
                <input type="number" class="form-control" name="ubrania[0][iloscMin]" min="1" value="1" required>
            </div>
            <div class="row mt-3 mb-3 col-md-11">
                <div class="col-md-2">
                    <label for="firma" class="form-label">Firma:</label>
                    <input type="text" class="form-control" id="firma" name="ubrania[0][firma]" required>
                </div>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="button" class="btn btn-success addUbranieBtn"><i class="bi bi-plus-lg"></i> Dodaj ubranie</button>
                <button type="button" class="btn btn-danger removeUbranieBtn ms-2" style="display: none;"><i class="bi bi-x-lg"></i> Usuń ubranie</button>
            </div>
        </div>
    </div>
    <div class="mb-5 col-md-6">
        <label for="id_uwagi" class="form-label">Uwagi:</label>
        <textarea id="id_uwagi" name="uwagi" rows="4" cols="50" class="form-control"></textarea>
    </div>
    <div class="d-flex align-items-center mb-3">
        <button type="submit" class="btn btn-primary submitBtn p-3">Dodaj zamówienie</button>
        <div id="loadingSpinner" class="spinner-border mb-2 ms-2" style="display: none;" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</form>

<script type="module" src="<?php echo $baseUrl; ?>/App.js"></script>

<?php include_once __DIR__ . '../../layout/footer.php'; ?>
