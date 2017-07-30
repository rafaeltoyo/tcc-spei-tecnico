<!DOCTYPE html>
<?php
include ('config.php');
require ('veref.php');

@session_start();

@$id_schedule = $_REQUEST['id_schedule'];

if (@$_REQUEST['id'] == "") {
    header("Location: viewSchedule.php");
    exit;
}

if (@$_REQUEST['loginn'] == "" && @$_REQUEST['action'] != "") {
    if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Erro!";} else {$_SESSION['mensagem'] = "Error!";}

    header ("Location: ".$_SERVER['PHP_SELF']."?id=".$_REQUEST['id']);
    exit;

}
if(@$_GET['action']=="Enviar" || @$_GET['action']=="Send") {
    $date = date('Y-m-d h:i:s');
    $query = "INSERT INTO requests (login,id_schedule,stats,date) VALUES ('{$_REQUEST['loginn']}','{$_REQUEST['id']}',1,'$date')";
    $result2 = mysql_query($query) or die (mysql_error());
    if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Convite enviado!";} else {$_SESSION['mensagem'] = "Invitation sent!";}
    header ("Location: ".$_SERVER['PHP_SELF']."?id=".$_REQUEST['id']);
    exit;
}
if(@$_GET['action']=="Cancelar" || @$_GET['action']=="Cancel") {
    $query = "DELETE FROM requests WHERE login LIKE '{$_REQUEST['loginn']}' AND id_schedule = '{$_REQUEST['id']}'";
    $result2 = mysql_query($query) or die (mysql_error());
    if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Convite cancelado!";} else {$_SESSION['mensagem'] = "Invitation canceled!";}

    header ("Location: ".$_SERVER['PHP_SELF']."?id=".$_REQUEST['id']);
    exit;
}

?>

<html lang="pt-br">
<head>
    <title> <?php echo ($_SESSION['lang'] == 'pt' ? 'Procurar usuários || PostNotes' : 'Search Users || PostNotes') ?> </title>
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
                        <h1> <?php echo ($_SESSION['lang'] == 'pt' ? 'Filtros de pesquisa' : 'Search filters') ?> </h1>
                        <li id="filter_title"><?php echo ($_SESSION['lang'] == 'pt' ? 'Procurar Usuários:' : 'Search Users') ?></li>
                        <li id="filter_select" style="width:200px;">
                            <a href="viewNotes.php?id=<?php echo $_REQUEST['id'] ?>" style="color:#E0E0E0;"><?php echo ($_SESSION['lang'] == 'pt' ? 'Ver Notas' : 'View Notes') ?></a>
                        </li>
                        <li id="filter_select" style="width:400px;">
                            <input type="text" name="filtro_nome" value="" placeholder="<?php echo ($_SESSION['lang'] == 'pt' ? 'Procurar por nome...' : 'Search by name...') ?>"/>
                            <input type="submit" id="idSearch" name="nSearch" value="a" />
                        </li>
                        <li id="filter_create">
                            <a href="requests.php?id=<?php echo $_REQUEST['id'] ?>"> <input type="button" id="seerequests" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Ver os pedidos' : 'View requests') ?>" /> </a>
                        </li>
                    </ul>
                </div>
            </div>
        </form>

        <p style="margin: 10px auto; text-align: center; font-size: 16pt; background-color: rgba(0,0,0,.1);"> <a href="createSchedule.php?id=<?php echo $_REQUEST['id'] ?>"> <?php echo ($_SESSION['lang'] == 'pt' ? 'EDITAR ESTA AGENDA' : 'EDIT THIS SCHEDULE') ?> </a> </p>

        <div id="schedule_group">
            <ul>
                <?php
				@$id_da_agenda_atual = $_REQUEST['id'];
                $query = "SELECT * FROM user WHERE login NOT LIKE '{$_SESSION['login']}'";
				$query .=" AND login NOT IN ( SELECT login FROM uschedule WHERE id_schedule = '{$id_da_agenda_atual}' )";
                $query .= (@$_POST['filtro_nome']!="" ? " AND name LIKE '%{$_POST['filtro_nome']}%'" : "" );
                $query .= " ORDER BY name";

                $result = mysql_query($query) or die (mysql_error());

                while($coluna = mysql_fetch_assoc($result)){
                    $nome = $coluna['name'];
                    $login = $coluna['login'];

                    ?>
                    <li id="schedules_body">
                        <ul>
                            <li id="schedules_text"><?php echo ($_SESSION['lang'] == 'pt' ? 'Nome:' : 'Name:') ?> <?php echo $nome; ?></li>
                            <li id="schedules_creator">Login: <?php echo $login; ?></li>
                            <li id="schedule_button">
                                <form action="#" method="post" name="Filter">
                                    <input type="hidden" id="user_current" name="user_current" value="<?php echo $coluna['login']; ?>" />

                                    <?php
                                    $query = " SELECT requests.* FROM requests INNER JOIN user
                                     ON user.login = requests.login
                                     WHERE requests.stats = 1
                                     AND user.login LIKE '{$coluna['login']}'
                                     AND requests.id_schedule = '{$id_da_agenda_atual}' ";
                                    $result2 = mysql_query($query) or die (mysql_error());
                                    $teste = mysql_fetch_assoc($result2);
                                    ?><input type="hidden" id="request" name="request" value="<?php echo $teste['id']; ?>" /><?php
                                    if (@$teste['login'] == ""){
                                    ?>
                                        <a href="searchUser.php?id=<?php echo $_REQUEST['id'] ?>&action=Enviar&loginn=<?php echo $coluna['login'] ?>">
                                        <input type="button" name="button" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Enviar' : 'Send') ?>">
                                        </a>
                                    <?php
                                    } else {
                                        ?>
                                        <a href="searchUser.php?id=<?php echo $_REQUEST['id'] ?>&action=Cancelar&loginn=<?php echo $coluna['login'] ?>">
                                        <input id="cancel" type="button" name="button" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Cancelar' : 'Cancel') ?>">
                                        </a>
                                    <?php
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