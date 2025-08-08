<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../app/helpers/UrlHelper.php';
$baseUrl = UrlHelper::getBaseUrl();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="base-url" content="<?php echo $baseUrl; ?>">
    <?php if (isset($_SESSION['csrf'])): ?>
    <meta name="csrf-token" content="<?php echo htmlspecialchars($_SESSION['csrf']); ?>">
    <?php endif; ?>
    <title>Logowanie</title>
    <link rel="icon" href="<?php echo $baseUrl; ?>/img/protectve-equipment.png" type="image/png">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/styl/css/custom.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/layout/login.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/styl/bootstrap/icons/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo $baseUrl; ?>/styl/bootstrap-select/css/bootstrap-select.css">
    <link href="<?php echo $baseUrl; ?>/styl/css/loader.css" rel="stylesheet" type="text/css"/>
    <script src="<?php echo $baseUrl; ?>/styl/js/jquery-3.3.1.min.js"></script>
    <script src="<?php echo $baseUrl; ?>/styl/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $baseUrl; ?>/styl/bootstrap-select/js/bootstrap-select.js"></script>
</head>
<body>
<div class="login-container">
    <div class="login-bg-overlay"></div>
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="hero-section mb-5 mb-lg-0">
                    <h1 class="display-4 fw-bold text-primary mb-3">System Zarządzania<br>Ubraniami</h1>
                    <p class="lead text-muted mb-4">Zaloguj się, aby zarządzać wydaniami ubrań i monitorować stan magazynu.</p>
                    <div class="d-none alert text-center" id="logInfoError"></div>
                    <div class="alert-container"></div>
                    <div class="features mt-5">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="feature-icon bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                        <i class="bi bi-box-seam text-primary"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">Magazyn</h5>
                                        <p class="text-muted small mb-0">Zarządzaj stanem odzieży</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="d-flex align-items-center">
                                    <div class="feature-icon bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                        <i class="bi bi-people text-primary"></i>
                                    </div>
                                    <div>
                                        <h5 class="mb-1">Pracownicy</h5>
                                        <p class="text-muted small mb-0">Monitoruj wydania</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 offset-lg-1">
                <div class="login-panel p-3 py-lg-5">
                    <div class="card shadow-lg border-0">
                        <div class="icon-container position-absolute" style="right: -50px;">
                            <i class="loader mx-2" id="loadingSpinner" style="display: none; width: 35px; height: 35px; line-height: 35px; font-size: 35px; border-width: 5px;"></i>
                        </div>
                        <div class="card-header bg-primary text-white fw-bold py-3">
                            <i class="bi bi-upc-scan me-2"></i> ZESKANUJ KOD
                        </div>
                        <div class="card-body p-4">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control form-control-lg" name="kodID" id="kodID" autocomplete="off" placeholder="Kod identyfikatora">
                                <label for="kodID">Kod identyfikatora</label>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="button" id="demoButton" class="btn btn-secondary btn-lg">
                                    <i class="bi bi-person-badge me-2"></i> Demo
                                </button>
                            </div>
                            <p class="text-muted mt-3"><i class="bi bi-info-circle me-2"></i> Zeskanuj kod swojego identyfikatora.</p>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <p class="text-muted small">© <?php echo date('Y'); ?> System Zarządzania Ubraniami</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalSprawdzam" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalSprawdzam" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow">
            <div class="modal-body p-4 text-center">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mb-0">Sprawdzanie danych...</p>
            </div>
        </div>
    </div>
</div>

<script type="module" src="<?php echo $baseUrl; ?>/script/auth/App.js"></script>
<script>
document.getElementById('kodID').focus();
document.getElementById('demoButton').addEventListener('click', function() {
    document.getElementById('kodID').value = 'ID|07202419|1';
    const event = new Event('change');
    document.getElementById('kodID').dispatchEvent(event);
});
</script>
</body>
</html>


