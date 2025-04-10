<?php 
function engToPL($date) {
    $engM = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    $polM = ['styczeń', 'luty', 'marzec', 'kwiecień', 'maj', 'czerwiec', 'lipiec', 'sierpień', 'wrzesień', 'październik', 'listopad', 'grudzień'];
    return str_replace($engM, $polM, $date);
}

function nowaData($months) {
    $date = new DateTime();
    $date->modify("+$months months");
    $formatDate = $date->format('d F Y');
    return engToPL($formatDate);
}
?>