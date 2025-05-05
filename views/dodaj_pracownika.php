<?php
include_once __DIR__ . '../../layout/header.php';
include_once __DIR__ . '../../app/database/Auth.php';
checkAccess(4);
?>

<div id="alertContainer"></div>

<h2 class="mb-4">Dodawanie pracownika</h2>
<form id="pracownikForm" action="<?php echo $baseUrl; ?>/app/forms/dodaj_pracownikaF.php" method="post" class="needs-validation">
    <div class="mb-3 col-md-5">
        <label for="imie" class="form-label">Imię:</label>
        <input type="text" class="form-control" id="imie" name="imie" required>
        <div class="invalid-feedback">
            Pole imię jest wymagane.
        </div>
    </div>

    <div class="mb-3 col-md-5">
        <label for="nazwisko" class="form-label">Nazwisko:</label>
        <input type="text" class="form-control" id="nazwisko" name="nazwisko" required>
        <div class="invalid-feedback">
            Pole nazwisko jest wymagane.
        </div>
    </div>

    <div class="mb-3 col-md-5">
        <label for="stanowisko" class="form-label">Stanowisko:</label>
        <input type="text" class="form-control" id="stanowisko" name="stanowisko" required>
        <div class="invalid-feedback">
            Pole stanowisko jest wymagane.
        </div>
    </div>

    <button type="submit" class="btn btn-primary submitBtn p-3">Dodaj pracownika</button>
</form>

<script type="module" src="<?php echo $baseUrl; ?>/App.js"></script>

<?php include_once __DIR__ . '../../layout/footer.php'; ?>
