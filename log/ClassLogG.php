<?php

class ClassLogG {

    protected $minH = 14;

    protected function panelLog() {
        echo '
        <div class="card position-relative" style="display: none;">
            <div class="icon-container position-absolute" style=" right: -50px;">
                <i class="loader mx-2" id="loadingSpinner" style="display: none; width: 35px; height: 35px; line-height: 35px; font-size: 35px; border-width: 5px;"></i>
            </div>
            <div class="card-header text-uppercase">
                Logowanie
            </div>
            <div class="card-body">
                <div class="input-group mb-3 mr-2 position-relative inputcontainer">
                    <span class="input-group-text"><i class="bi bi-person-workspace"></i></span>
                    <input type="text" class="form-control" maxlength="30" placeholder="Imię i nazwisko" id="username" pattern="[A-Za-zżźćńółęąśŻŹĆĄŚĘŁÓŃ]*" autocomplete="off" required>
                    <ul id="suggestions" class="list-group position-absolute" style="display: none; z-index: 1000; width: 100%; top: 100%;"></ul>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                    <input type="password" class="form-control" placeholder="Hasło" id="password">
                </div>
                <div class="text-end" style="margin-right:-1em">
                    <button type="button" name="log" class="btn btn-secondary" id="loginButton">
                        ZALOGUJ <i class="bi bi-box-arrow-in-right"></i>
                    </button>
                </div>
            </div>
        </div>';
    }

    protected function panelScan() {
        echo '
            <div class="card">
            <div class="icon-container position-absolute" style=" right: -50px;">
                <i class="loader mx-2" id="loadingSpinner" style="display: none; width: 35px; height: 35px; line-height: 35px; font-size: 35px; border-width: 5px;"></i>
            </div>
                <div class="card-header" style="font-weight:600;">
                    ZESKANUJ KOD
                </div>
                <div class="card-body">
                    <input type="password" class="form-control" name="kodID" id="kodID" autocomplete="off">
                    <br />
                    Zeskanuj kod swojego identyfikatora.
                    <!-- 
                    <div class="text-end" style="margin-right:-1em">
                        <button type="button" name="logKod" class="btn btn-secondary">
                            ZALOGUJ <i class="bi bi-box-arrow-in-right"></i>
                        </button>
                    </div>
                    -->
                </div>
            </div>
            <script>document.getElementById("kodID").focus();</script>';
    }
    
    public function ekranLog() {
        echo'
        <div class="container ">
            <div class="row" style="margin-top:5em; margin-bottom:3em;">
                <div class="col-12 text-end"><img src="" alt="" height="170"></div>
            </div>
            <br />
            <div class="d-none alert text-center" id="logInfoError"></div>
            <div class="alert-container"></div>
            <div class="row d-flex justify-content-center bd-highlight">

            <div class="row d-flex justify-content-center bd-highlight">
                <div class="col-5 text-center mt-3 mx-3">';
                $this->panelScan();
                echo '</div>
                </div>
        <br /><br />
       
       <div class="text-end"></div>';
       /*
       <div class="col-5 text-center mt-3 mx-3">';
                $this->panelLog();
                echo '</div>
                        <div class="col-5 text-center mt-3 mx-3">';
                $this->panelScan();
                echo '</div>
       */
        // modal wczytywanie
        echo '
            <div class="modal fade" id="modalSprawdzam" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalSprawdzam" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="wait mt-4 mb-4"><div class="wczytuje" id="wczytuje"><div class="loader"></div></div></div>
                        </div>
                    </div>
                </div>
            </div>';
        
         //__________JS___________-
        echo'
            <script type="module" src="./sesja/LoginValidator.js"></script>
            <script type="module" src="./sugestie/App.js"></script>
        ';
    }
    
}
?>
