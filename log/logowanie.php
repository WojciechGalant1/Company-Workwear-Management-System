<?php
session_start();

echo '<!DOCTYPE html><html lang="pl"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SYSTEM</title>
    <link rel="stylesheet" href="../styl/css/custom.css">
    <script type="module" src="../log/sesja/LoginValidator.js"></script>
    <link rel="stylesheet" href="../styl/bootstrap-select/css/bootstrap-select.css">
    <script src="../styl/js/jquery-3.3.1.min.js"></script>
    <script src="../styl/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../styl/bootstrap-select/js/bootstrap-select.js"></script>
    <link rel="stylesheet" href="../styl/bootstrap/icons/bootstrap-icons.css">
    <link href="../styl/css/loader.css" rel="stylesheet" type="text/css"/>
</head><body>';

include_once 'ClassLogG.php';
$log = new ClassLogG();
$log->ekranLog();

echo '
</body></html>';