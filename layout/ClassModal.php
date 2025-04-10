<?php
class ClassModal {
    public function anulujModal() {
        echo '
<div class="modal fade" id="confirmCancelModal" tabindex="-1" aria-labelledby="confirmCancelModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmCancelModalLabel">Potwierdzenie anulowania</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
               Czy na pewno chcesz anulować to wydanie?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                <button type="button" class="btn btn-primary" id="confirmCancelBtn">Potwierdź</button>
            </div>
        </div>
    </div>
</div>
';
    }

    public function zniszczoneModal() {
        echo '
<div class="modal fade" id="confirmDestroyModal" tabindex="-1" aria-labelledby="confirmDestroyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmDestroyModalLabel">Potwierdzenie zwrotu zniszczonego ubrania</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Czy na pewno chcesz zapisać to ubranie jako zniszczone?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuluj</button>
                <button type="button" class="btn btn-primary" id="confirmDestroyBtn">Potwierdź</button>
            </div>
        </div>
    </div>
</div>
';
    }
   
}

?>