<?php
@session_start();

include("config.php");

if(@$_REQUEST['botao'] == "Sair" || $_REQUEST['logout']=="true"){
    @session_unset();
    @session_destroy();
}
header('Location: index.php');
?>