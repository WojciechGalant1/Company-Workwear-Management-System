<?php

/**
 * Configuration for JavaScript modules required by each page
 */
return array(
    'dodaj_ubranie.php' => '',
    'dodaj_pracownika.php' => 'AlertManager',
    'wydaj_ubranie.php' => 'UbraniaManager,AlertManager,WorkerSuggestions,ModalWydajUbranie,ChangeStatus,DomUpdateSystem',
    'prac_lista.php' => 'AlertManager,ModalEdytujPracownika',
    'dodaj_zamowienie.php' => 'AlertManager,ProductSuggestions,CheckUbranie',
    'historia.php' => 'WorkerSuggestions,ChangeStatus,AnulujWydanie,ZniszczUbranie',
    'raport.php' => 'RedirectStatus,ChangeStatus',
    'mag_lista.php' => 'AlertManager,EdycjaUbranie,DomUpdateSystem',
    'historia_ubran.php' => 'HistoriaUbranSzczegoly',
    'default' => ''
); 