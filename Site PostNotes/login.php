<?php
@session_start();

include("config.php");

if ($_REQUEST['button']=="Enter" || $_REQUEST['button']=="Entrar") {
    $_SESSION['mensagem'] = ($_SESSION['lang']=="pt"? "Dados incorretos!" : "Login/Password invalid!");
    $passsenhawordcryptsenha = encrypt($_POST['nLoginPass']);
    $result = mysql_query( "SELECT * FROM user WHERE login='{$_POST['nLoginLogin']}' AND pass='$passsenhawordcryptsenha' " ) or die (mysql_error());
    while ($coluna = mysql_fetch_array($result)) {

        if ($_SESSION['lang'] != "") {
            $lang_temp = $_SESSION['lang'];
        }
        @session_unset();
        @session_destroy();
        @session_start();

        if (isset($lang_temp)) {
            $_SESSION['lang'] = $lang_temp;
        }
        $_SESSION['login'] = $coluna['login'];
        $_SESSION['mensagem'] = ($_SESSION['lang']=="pt"? "Bem vindo!" : "Welcome!");
        header('Location: viewNotes.php');
        /*
        ob_start();
        header("Location: viewNotes.php");
        ob_end_flush();
        exit;
        */
    }
}
if ($_REQUEST['button']=="Logout" || $_REQUEST['logout']=="true" || $_REQUEST['button']=="Sair") {
    if ($_SESSION['lang'] != "") {
        $lang_temp = $_SESSION['lang'];
    }
    @session_unset();
    @session_destroy();
    if (isset($lang_temp)) {
        $_SESSION['lang'] = $lang_temp;
    }
}
header('Location: index.php');
/*
ob_start();
header("Location: index.php");
ob_end_flush();
exit;
*/