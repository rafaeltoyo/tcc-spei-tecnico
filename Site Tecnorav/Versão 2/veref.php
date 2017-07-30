<?php
if (!isset($_SESSION['login'])) {
    $_SESSION['mensagem'] = "Você precisa logar para fazer isso!";
    ob_start();
    header("Location: index.php");
    ob_end_flush();
    /*
    echo "<script> Redirect('index.php') </script>";
    ob_start();
    header("Location: index.php");
    ob_end_flush();
    */
    exit;
}

?>