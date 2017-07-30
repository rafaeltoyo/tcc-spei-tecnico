<!DOCTYPE html>
<?php
include('config.php');
//include('function.php');
require('veref.php');

@session_start();

if (@$_REQUEST['id'] != "" && @$_REQUEST['button']=="") {
    $this_schedule_is_your = false;
    $result = mysql_query( "SELECT * FROM schedule WHERE id = '{$_REQUEST['id']}'" ) or die (mysql_error());
    $_SESSION['mensagem'] = "This Schedule doesn't exist!";
    while ($coluna = mysql_fetch_array($result)) {
        if ( $coluna['administrator'] == $_SESSION['login'] ) {
            $this_schedule_is_your = true;
            break;
        }
        $_SESSION['mensagem'] = "You don't belong to this schedule!";
    }
    if (!$this_schedule_is_your) {
        echo "<script> Redirect('viewSchedule.php') </script>";
        /*
        ob_start();
        header("Location: viewSchedule.php");
        ob_end_flush();
        exit;
        */
    } else {
        unset($_SESSION['mensagem']);
    }
}

?>

<html lang="pt-br">

<head>
    <title> PostNotes - CREATE SCHEDULE </title>
    <meta charset="UTF-8" />
    <link rel="stylesheet" type="text/css" href="_css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="_css/indexstyle.css"/>
    <script>
        function ClearAllFields () {
            document.getElementById("nameSchedule").value = "";
            document.getElementById("privacitySchedule").value = "";
            document.getElementById("typeSchedule").value = "";
        }
    </script>
</head>

<body>
<?php
include("menu.php");
?>

<header id="cabecalho">

</header>

<div id="min_size">
    <div id="tableRegister">


    </div>
</div>
<?php require("footer.php"); ?>
</div>
</body>
</html>