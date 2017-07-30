<?php
@session_start();

if (!isset($_SESSION['login'])) {
    if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Você precisa logar para fazer isso!";} else {$_SESSION['mensagem'] = "You need to log to do access this page!";}
    /*
    echo "<script> Redirect('index.php') </script>";
    ob_start();
    header("Location: index.php");
    ob_end_flush();
    */
    exit;
}