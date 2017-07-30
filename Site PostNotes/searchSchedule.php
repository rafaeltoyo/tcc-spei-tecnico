<!DOCTYPE html>
<?php
include('config.php');
require('veref.php');

@session_start();

/* Pega as agendas de suas requisições */
$agendas_pendentes = "";
$query = "SELECT * FROM requests WHERE login LIKE '{$_SESSION['login']}'";
$result = mysql_query($query) or die (mysql_error());
while ($coluna = mysql_fetch_array($result)) {
    $agendas_pendentes = $coluna['id_schedule'];
}

/* Vereficador de ação */
if (@$_REQUEST['button'] != "") {
    /* Verefica se não há já uma requisição igual a ser criada */
    $query = "SELECT * FROM requests WHERE login LIKE '{$_SESSION['login']}' AND id_schedule = '{$_POST['current_schedule']}'";
    $result = mysql_query($query) or die (mysql_error());
    while ($coluna = mysql_fetch_array($result)) {
        if ($_REQUEST['button'] == "Cancel" || $_REQUEST['button'] == "Cancelar") { // SE achar uma igual e o comando for deletar, exclui a requisição
            $query = "DELETE FROM requests WHERE id_schedule = '{$_POST['current_schedule']}' AND login = '{$_SESSION['login']}'";
			$result = mysql_query($query) or die (mysql_error());
			//$_SESSION['mensagem'] = "Pedido cancelado!";
			/*Feito isso pq n funcionou o "=" no operador ternario*/ if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Pedido cancelado!";} else { $_SESSION['mensagem'] = "Request canceled!";}
			//($_SESSION['lang'] == 'pt' ? '$_SESSION[mensagem] = Pedido cancelado!' : '$_SESSION[mensagem] = Request canceled!');

            header ("Location: ".$_SERVER['PHP_SELF']);
            exit;

        } else { // Se não dá erro, a requisição já existe
			if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Você já enviou um pedido a essa agenda!";} else { $_SESSION['mensagem'] = "You have already sent a request to this schedule!";}
            echo "<script> Redirect('.{$_SERVER['PHP_SELF']}') </script>";

            header ("Location: ".$_SERVER['PHP_SELF']);
			exit;

        }
    }
    /* Comando para solicitar a entrada nessa agenda */
    if ($_REQUEST['button'] == "Solicitar" || $_REQUEST['button'] == "Request") {
        $date = date('Y-m-d h:i:s');
        $query = "INSERT INTO requests (login,id_schedule,stats,date) VALUES ('{$_SESSION['login']}','{$_POST['current_schedule']}',0,'$date')";
        $result = mysql_query($query) or die (mysql_error());
        if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Pedido enviado!";} else { $_SESSION['mensagem'] = "Request sent!";}
        header ("Location: ".$_SERVER['PHP_SELF']);
        exit;
    /* Comando para entrar direto na agenda */
    } else if ($_REQUEST['button'] == "Entrar" || $_REQUEST['button'] == "Enter") {
        /* Verefica se a agenda é pública */
        $query = "SELECT * FROM schedule WHERE id = '{$_POST['current_schedule']}' AND priv_schedule = 0";
        $result = mysql_query($query) or die (mysql_error());
        $coluna = mysql_fetch_assoc($result);
        if ($coluna['name'] != "") {
            /* Verefica se você já não está na agenda */
            $query = "SELECT * FROM uschedule WHERE id_schedule = '{$_POST['current_schedule']}' AND login = '{$_SESSION['login']}'";
            $result = mysql_query($query) or die (mysql_error());
            $coluna2 = mysql_fetch_assoc($result);
            if ($coluna2['id'] != "") {
                if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Você já está nessa agenda!";} else { $_SESSION['mensagem'] = "You already are in this schedule!";}
                header ("Location: ".$_SERVER['PHP_SELF']);
                exit;
            } else {
                /* Entra na agenda */
                $query = "INSERT INTO uschedule (login,id_schedule,rank,follow) VALUES ('{$_SESSION['login']}','{$_POST['current_schedule']}',0,0)";
                $result = mysql_query($query) or die (mysql_error());
                if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Bem vindo a uma nova agenda!";} else { $_SESSION['mensagem'] = "Welcome to a new schedule!";}
                header ("Location: ".$_SERVER['PHP_SELF']);
                exit;
            }
        } else {
            if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Você não pode entrar nessa agenda!";} else { $_SESSION['mensagem'] = "You can't enter in this schedule!";}
            header ("Location: ".$_SERVER['PHP_SELF']);
            exit;
        }
    }


}

?>

<html lang="pt-br">
<head>
    <title> <?php echo ($_SESSION['lang'] == 'pt' ? 'Procurar Agendas || PostNotes' : 'Search Schedules') ?> </title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" type="text/css" href="_css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="_css/indexstyle.css"/>
    <link rel="stylesheet" type="text/css" href="_css/scheduleSearch.css"/>
</head>
<body>
<?php
include("menu.php");
?>

<header id="cabecalho">

</header>
<div id="min_size"><div id="center_box">
        <!-- Lista de filtros de pesquisa das agendas -->
        <form action="#" method="post" name="Filter">
            <div id="filter_menu">
                <div id="filter_menu_interior">
                <ul>
                    <h1> <?php echo ($_SESSION['lang'] == 'pt' ? 'Procurar Filtros' : 'Search Filters') ?> </h1>
                    <li id="filter_title"><?php echo ($_SESSION['lang'] == 'pt' ? 'Procurar Agendas:' : 'Search Schedules') ?></li>
                    <li id="filter_select">
                        Nome: <input type="text" id="filtroNome" name="filtroNome" value="<?php echo @$_POST['filtroNome'] ?>" />
                        <select id="idFilter" name="nFilter" onchange="showFilter()">
                            <option value="" <?php echo (@$_POST['nFilter'] == "" ? "selected" : "") ?>> <?php echo ($_SESSION['lang'] == 'pt' ? 'Mostrar todas as agendas.' : 'Show all the schedules') ?> </option>
                            <option value="P" <?php echo (@$_POST['nFilter'] == "P" ? "selected" : "") ?>> <?php echo ($_SESSION['lang'] == 'pt' ? 'Mostrar apenas as agendas publicas.' : 'Show only the public schedules') ?> </option>
                            <option value="A" <?php echo (@$_POST['nFilter'] == "A" ? "selected" : "") ?>> <?php echo ($_SESSION['lang'] == 'pt' ? 'Mostrar apenas as agendas fechadas.' : 'Show only closed schedules') ?> </option>
                        </select>
                        <input type="submit" id="idSearch" name="nSearch" value="a" />
                    </li>
                    <li id="filter_create">
                        <a href="createSchedule.php"> <input type="button" id="createbutton" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Criar Nova Agenda' : 'Create New Schedule') ?>" /> </a>
                    </li>
                </ul>
                </div>
            </div>
        </form>


        <div id="schedule_group">
            <ul>
                <?php

                /* Pegar pedidos já realizados */
                $query = "SELECT * FROM requests WHERE login = '{$_SESSION['login']}' AND stats = 0";
                $result = mysql_query($query) or die (mysql_error());
                $i = 0;
                while ($coluna = mysql_fetch_assoc($result)) {
                    $id_requests[$i] = $coluna['id_schedule'];
                    $i ++;
                }

                /* Mostrar agendas disponiveis */
                $query = "SELECT * FROM schedule WHERE priv_schedule != 2 AND id NOT IN (SELECT id_schedule FROM uschedule WHERE login = '{$_SESSION['login']}') AND administrator NOT LIKE '{$_SESSION['login']}'";
                $query .= ( @$_POST['nFilter'] == "P" ? " AND priv_schedule = 0" : (@$_POST['nFilter'] == "A" ? " AND priv_schedule = 1" : "") );
                $nome = @$_POST['filtroNome'];
                $query .= ( @$_POST['filtroNome'] == "" ? "" : " AND name LIKE '%$nome%' " );
                $query .= " ORDER BY name";
                $result = mysql_query($query) or die (mysql_error());

                while($coluna = mysql_fetch_assoc($result)){
                    ?>
                    <li id="schedules_body">
                        <ul>
                            <li id="schedules_text"><?php echo $coluna['name']; ?></li>
                            <li id="schedules_creator">Admin: <?php echo $coluna['administrator']; ?></li>
                            <li id="schedule_button">
                                <form action="#" method="post" name="Filter">
                                    <input type="hidden" id="current_schedule" name="current_schedule" value="<?php echo $coluna['id']; ?>" />
                            <?php
                            if ( @in_array($coluna['id'],$id_requests)) {
                                ?><input type="submit" name="button" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Cancelar' : 'Cancel') ?>"><?php
                            } else if ($coluna['priv_schedule'] == 0) {
                                ?><input type="submit" name="button" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Entrar' : 'Enter') ?>"><?php
                            } else if ($coluna['priv_schedule'] == 1) {
                                ?><input type="submit" name="button" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Solicitar' : 'Request') ?>"><?php
                            }
                            ?>
                                </form>
                            </li>
                        </ul>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>


    </div></div>
<?php require("footer.php"); ?>
</body>
</html>