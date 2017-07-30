<!DOCTYPE html>
<?php
include("config.php");
require("veref.php");

@session_start();

if (@$_REQUEST['id'] != "") {
    $query = "SELECT * FROM schedule
    WHERE id = '{$_REQUEST['id']}'
    AND ( administrator LIKE '{$_SESSION['login']}'
    OR id IN (SELECT id_schedule FROM uschedule
      WHERE login LIKE '{$_SESSION['login']}'
      AND id_schedule = '{$_REQUEST['id']}'
      AND rank = 1) )";
    $result = mysql_query("$query") or die (mysql_error());
    $coluna = mysql_fetch_assoc($result);
    if ($coluna['id'] == "") {
        if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Agenda inválida!";} else {$_SESSION['mensagem'] = "Invalid schedule!";}
        echo "<script> Redirect('viewSchedule.php') </script>";
        /*
        ob_start();
        header ("Location: viewSchedule.php");
        ob_end_flush();
        exit;
        */
    }
} else {
    echo "<script> Redirect('viewSchedule.php') </script>";
    /*
    ob_start();
    header ("Location: viewSchedule.php");
    ob_end_flush();
    exit;
    */
}

if(@$_REQUEST['button']=="Aceitar" || @$_REQUEST['button']=="Accept"){
    // Pega o pedido aceito
    $query = "SELECT requests.*,schedule.administrator AS admin FROM requests INNER JOIN schedule ON requests.id_schedule = schedule.id WHERE requests.id = '{$_POST['id_request']}' AND schedule.administrator = '{$_SESSION['login']}' AND requests.stats = 0";
    $result = mysql_query("$query") or die (mysql_error());
    $coluna = mysql_fetch_assoc($result);
    $id = $coluna['id_schedule'];
    $login_request = $coluna['login'];
    $admin_request = $coluna['admin'];

    // Verefica se achou o pedido no banco
    if ($admin_request == $_SESSION['login']) {
        // Verefica se o membro do pedido já não está na agenda
        $query = "SELECT * FROM uschedule WHERE id_schedule = '$id' AND login = '$login_request' ";
        $result2 = mysql_query("$query") or die (mysql_error());
        $coluna2 = mysql_fetch_assoc($result2);
        $id_request = $coluna2['id_uschedule'];

        if ($id_request == "") {
            $query = "INSERT INTO uschedule(id_schedule,login,rank,follow) VALUES('$id','$login_request',0,0)";
            $result = mysql_query("$query") or die(mysql_error());

            $query = "DELETE FROM requests where login = '$login_request' and id_schedule = '$id'";
            $result = mysql_query("$query") or die(mysql_error());

            if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Pedido aceito!";} else {$_SESSION['mensagem'] = "Request accepted!";}
            echo "<script> Redirect('requests.php?id='.{$_REQUEST['id']}') </script>";
            /*
            ob_start();
            header ("Location: requests.php?id=".$_REQUEST['id']);
            ob_end_flush();
            exit;
            */
        }

        if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Usuário já está nessa agenda!";} else {$_SESSION['mensagem'] = "User is already in this schedule!";}
        echo "<script> Redirect('requests.php?id='.{$_REQUEST['id']}') </script>";
        /*
        ob_start();
        header ("Location: requests.php?id=".$_REQUEST['id']);
        ob_end_flush();
        exit;
        */
    }

    if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Pedido não existe!";} else {$_SESSION['mensagem'] = "Request doesn't exist!";}
    echo "<script> Redirect('requests.php?id='.{$_REQUEST['id']}') </script>";
    /*
    ob_start();
    header ("Location: requests.php?id=".$_REQUEST['id']);
    ob_end_flush();
    exit;
    */
}

if(@$_REQUEST['button']=="Rejeitar" || @$_REQUEST['button']=="Reject"){
    $query = "SELECT requests.*,schedule.* FROM requests INNER JOIN schedule WHERE requests.id_schedule = schedule.id AND schedule.administrator = '{$_SESSION['login']}' AND requests.stats = 0";
    $result = mysql_query("$query") or die (mysql_error());
    $coluna = mysql_fetch_assoc($result);
    $id = $coluna['id_schedule'];
    $login_request = $coluna['login'];
    $query = "DELETE FROM requests where login = '$login_request' and id_schedule = '$id'";
    $result = mysql_query("$query") or die(mysql_error());

    if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Pedido recusado!";} else {$_SESSION['mensagem'] = "Request rejected!";}
    echo "<script> Redirect('requests.php?id='.{$_REQUEST['id']}') </script>";
    /*
    ob_start();
    header ("Location: requests.php?id=".$_REQUEST['id']);
    ob_end_flush();
    exit;
    */
}
?>



<html lang="pt-br">
<head>
	<title> <?php echo ($_SESSION['lang'] == 'pt' ? 'Pedidos || PostNotes' : 'Requests || PostNotes') ?></title>
	<meta charset="UTF-8" />
	<link rel="stylesheet" type="text/css" href="_css/menu.css">
	<link rel="stylesheet" type="text/css" href="_css/indexstyle.css">
    <link rel="stylesheet" type="text/css" href="_css/member.css"/>
</head>
<body>
<?php
include ("menu.php");
?>

<header id="cabecalho">

</header>

<div id="min_size"><div id="center_box">

    <h1> <?php echo ($_SESSION['lang'] == 'pt' ? 'Os pedidos para entrar na agenda:' : 'Requests to join in this schedule:') ?> </h1>

    <div id="List"><ul>
        <?php
        $verefica = false;
        $query = "SELECT * FROM requests WHERE id_schedule = '{$_REQUEST['id']}' AND stats = 0";
        $result = mysql_query("$query") or die (mysql_error());
        while ($coluna = mysql_fetch_assoc($result)){
             $verefica = true;
            ?>
                <li id="requests_body">
                    <ul>
                        <form action=# method=POST name=form1>
                        <li id="user_name">"<?php echo $coluna['login'] ?>" <?php echo ($_SESSION['lang'] == 'pt' ? 'solicitou para participar da sua agenda.' : 'asked to join on your schedule') ?></li>
                        <li><input type="submit" name="button" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Aceitar' : 'Accept') ?>" /></li>
                        <li><input type="submit" name="button" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Rejeitar' : 'Reject') ?>" /></li>
                        <input type="hidden" id="id_request" name="id_request" value="<?php echo $coluna['id'] ?>" />
                        </form>
                    </ul>
                </li>
        <?php
        }
        if ( !$verefica ){
            ?>
            <li> <?php echo ($_SESSION['lang'] == 'pt' ? "Você não tem pedidos" : "You don't have requests") ?> </li>
        <?php
        }
        ?>
    </ul></div>

        <h1> <a href="searchUser.php?id=<?php echo $_REQUEST['id'] ?>"><?php echo ($_SESSION['lang'] == 'pt' ? 'VOLTAR' : 'BACK') ?></a> </h1>

</div></div>
<?php require("footer.php"); ?>
</body>
</html>