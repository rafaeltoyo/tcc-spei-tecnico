<!DOCTYPE html>
<?php
include('config.php');
require('veref.php');
@session_start();
?>

<html lang="pt-br">
<head>
    <title> <?php echo ($_SESSION['lang'] == 'pt' ? 'Agendas || PostNotes' : 'Schedules || PostNotes') ?> </title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" type="text/css" href="_css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="_css/indexstyle.css"/>
    <link rel="stylesheet" type="text/css" href="_css/schedule.css"/>
</head>
<body>
<?php
include("menu.php");
?>

<header id="cabecalho">

</header>

<div id="min_size">
<div id="schedules">
    <h1><?php echo ($_SESSION['lang'] == 'pt' ? 'Suas Agendas:' : 'Your Schedules') ?></h1>

    <div style="width: 800px; text-align: center; margin: 50px auto;background-color: #C1C1C1"><iframe src="viewScheduleFrame.php" name="schedules"></iframe></div>

</div>
</div>
<?php require("footer.php"); ?>
</body>
</html>