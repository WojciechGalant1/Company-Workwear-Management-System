<?php

class ClassLogG {

    protected $minH = 14;



    protected function panelScan() {
        echo '
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
                    <p class="text-muted"><i class="bi bi-info-circle me-2"></i> Zeskanuj kod swojego identyfikatora.</p>
                </div>
            </div>
            <script>document.getElementById("kodID").focus();</script>';
    }
    
    public function ekranLog() {
        echo'
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
                        <div class="login-panel p-3 py-lg-5">';
                        $this->panelScan();
                        echo '
                            <div class="text-center mt-4">
                                <p class="text-muted small">© ' . date('Y') . ' System Zarządzania Ubraniami</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
   
        <!-- modal wczytywanie -->
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
        
        <!-- Scripts -->
        <script type="module" src="./sesja/LoginValidator.js"></script>
        <script type="module" src="./sugestie/App.js"></script>
        ';
    }
    
}

?>


