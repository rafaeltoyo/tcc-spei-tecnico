<?php
@session_start();
$con = @mysql_connect('localhost','root','');
$db = @mysql_select_db('tcc');

include("function.php");

if(!$con || !$db) {
    echo mysql_error();
}

if (isset($_SESSION['mensagem'])) {
    echo "<script> alert('{$_SESSION['mensagem']}');</script>";
    unset ($_SESSION['mensagem']);
}

// Altera idioma
if(getUserLanguage() == "" || @$_SESSION['lang'] == ""){
	$_SESSION['lang']=getUserLanguage();
} else if (@$_REQUEST['idioma'] == "pt") {
	$_SESSION['lang']="pt";
    $url_temp = $_SERVER['REQUEST_URI'];
    $url_temp = str_replace("?idioma=pt","",$url_temp);
    header("Location: ".$url_temp);
    exit;
} else if (@$_REQUEST['idioma'] != "") {
	$_SESSION['lang']="en";
    $url_temp = $_SERVER['REQUEST_URI'];
    $url_temp = str_replace("?idioma=en","",$url_temp);
    header("Location: ".$url_temp);
    exit;
}