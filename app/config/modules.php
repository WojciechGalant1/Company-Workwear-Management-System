<?php

/**
 * Configuration for JavaScript modules required by each page
 */
return array(
    'dodaj_ubranie.php' => '',
    'dodaj_pracownika.php' => 'AlertManager',
    'wydaj_ubranie.php' => 'UbraniaManager,AlertManager,UserSuggestions,ModalWydajUbranie,ChangeStatus',
    'prac_lista.php' => 'AlertManager,ModalEdytujPracownika',
    'dodaj_zamowienie.php' => 'AlertManager,ProductSuggestions,CheckUbranie',
    'historia.php' => 'UserSuggestions,ChangeStatus,AnulujWydanie,ZniszczUbranie',
    'raport.php' => 'RedirectStatus,ChangeStatus',
    'mag_lista.php' => 'AlertManager,EdycjaUbranie',
    'historia_ubran.php' => 'HistoriaUbranSzczegoly',
    'default' => ''
); 