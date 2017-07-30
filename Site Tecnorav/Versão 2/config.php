<!DOCTYPE html>
<?php
@session_start();
$con = @mysql_connect('mysql05-farm51.kinghost.net','postnotes','tecno123');
$db = @mysql_select_db("postnotes");



if(!$con || !$db) {
    echo mysql_error();
}

if (isset($_SESSION['mensagem'])) {
    echo "<script> alert('{$_SESSION['mensagem']}');</script>";
    unset ($_SESSION['mensagem']);
}
?>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" type="text/css" href="_css/indexstyle.css" />
</head>
</html>