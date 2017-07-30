<!DOCTYPE html>
<?php
date_default_timezone_set("Brazil/East");
@$con = mysql_connect('localhost','root','');
@$db = mysql_select_db('tcc');
@session_start();

if(!$con || !$db)
{
    echo "<pre>";
    echo mysql_error();
    echo "</pre>";
}
?>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <title>POSTNOTES</title>

    <link rel="stylesheet" type="text/css" href="../_css/adminEstrutura.css" />
    <script>
        function getDayName () {
            var data = new Date();
            var semana = data.getDay();
            switch (semana) {
                case 0: return "Dom"; break;
                case 1: return "Seg"; break;
                case 2: return "Ter"; break;
                case 3: return "Qua"; break;
                case 4: return "Qui"; break;
                case 5: return "Sex"; break;
                case 6: return "Sab"; break;
            }
        }
        function relogio(){
            var data = new Date();
            var ano = data.getFullYear();
            var mes = data.getMonth();
            mes = mes + 1;
            if (mes < 10) {
                mes = "/0" + mes;
            } else {
                mes = "/" + mes;
            }
            var dia = data.getDate();
            if (dia < 10) {
                dia = " - 0" + dia;
            } else {
                dia = " - " + dia;
            }
            var horas = data.getHours();
            if (horas < 10) {
                horas = "  0" + horas;
            } else {
                horas = "  " + horas;
            }
            var minutos = data.getMinutes();
            if (minutos < 10) {
                minutos = ":0" + minutos;
            } else {
                minutos = ":" + minutos;
            }
            var segundos = data.getSeconds();
            if (segundos < 10) {
                segundos = ":0" + segundos;
            } else {
                segundos = ":" + segundos;
            }
            var semana = getDayName();

            var teste = new Date ();
            var exibe = document.getElementById("horas");
            //horas + minutos + segundos + dia + mes + "/" + ano
            exibe.innerHTML = horas + minutos + segundos + "(" + semana + ")" + dia + mes + "/" + ano;
        }
        setInterval(relogio, 1000);


    </script>

</head>
<body>
<nav id="menu">
    <h1> RELATORIOS </h1>
    <ul>
        <a href="../index.php"><li>HOME</li></a>
        <a href="../reportSchedule.php"><li>SCHEDULE REPORT</li></a>
        <a href="reportUser.php"><li>USER REPORT</li></a>
        <li style="display: block; float: right;"> <p style="text-align: right;"> Bem vindo ADMIN - Agora são: <span id="horas"> </span> <!-- AQUI FICA A DATA E HORA --> </p> </li>
    </ul>
</nav>
<div id="body">