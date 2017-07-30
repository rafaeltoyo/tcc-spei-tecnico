<!DOCTYPE html>
<?php
include('config.php');
require('veref.php');
@session_start();

if (@$_REQUEST['id'] != "") {
    // Procura uma agenda cujo id é o mesmo da url, e se essa agenda possui uma relação com o usuario.
    $query = "SELECT schedule.id, schedule.name FROM schedule INNER JOIN uschedule ON schedule.id = uschedule.id_schedule WHERE schedule.id = '{$_REQUEST['id']}' AND uschedule.login LIKE '{$_SESSION['login']}'";
    $result = mysql_query($query) or die (mysql_error());
    $vereficador_agenda = mysql_fetch_assoc($result);

    if ($vereficador_agenda['id'] != null) {
        // Agenda encontrada
        $_SESSION['schedule'] = $vereficador_agenda['name'];
    } else {
        // Agenda não encontrada
        if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Agenda Inválida!";} else {$_SESSION['mensagem'] = "Invalid Schedule!";}
        // Limpar a Url
        echo "<script> Redirect('viewSchedule.php') </script>";
        /*
        ob_start();
        header ("Location: viewSchedule.php");
        ob_end_flush();
        exit;
        */
    }
}


@$id_schedule = $_REQUEST['id'];

// <input type="submit" id="Exit" name="Exit" value="<?php echo (@$verefica['id'] != "" ? "DELETE" : "LEAVE" ) "
//onclick="return confirm('Are you sure you want to continue')"  />
if(@$_REQUEST['Exit'] == "DELETAR" || @$_REQUEST['Exit'] == "DELETE"){
    $query = "SELECT * FROM schedule WHERE administrator = '{$_SESSION['login']}' AND id = '{$_REQUEST['id']}'";
    $result = mysql_query($query) or die (mysql_error());

    $coluna = mysql_fetch_assoc($result);
    $administrator = $coluna['administrator'];
    if($coluna['administrator'] == $_SESSION['login'] && $id_schedule == $_REQUEST['id']){
        $query = "DELETE FROM schedule WHERE id = '$id_schedule' AND administrator = '$administrator'";
        $result = mysql_query($query) or die (mysql_error());
        $query = "DELETE FROM notes WHERE id_schedule = '$id_schedule'";
        $result = mysql_query($query) or die (mysql_error());

        $query = "DELETE FROM uschedule WHERE id_schedule = '$id_schedule'";
        $result = mysql_query($query) or die (mysql_error());

        if($_SESSION['lang'] == "pt"){
		if($result){
            echo "<script> alert('Agenda Excluida com sucesso');top.location.href='viewSchedule.php' </script>";
        }
        else{
            echo "<script> alert('Não é possível excluir a agenda pois você nao é adm') </script>";
        }
		} else {
		if($result){
            echo "<script> alert('Schedule deleted successfully');top.location.href='viewSchedule.php' </script>";
        }
        else{
            echo "<script> alert('Could not delete the schedule because you are not the admin') </script>";
        }
		}
    }
}
if(@$_REQUEST['Exit'] == "SAIR" || @$_REQUEST['Exit'] == "LEAVE"){
    $query = "SELECT * FROM uschedule WHERE login = '{$_SESSION['login']}' AND id_schedule = '{$_REQUEST['id']}'";
    $result = mysql_query($query) or die (mysql_error());

    $coluna = mysql_fetch_assoc($result);
    $login = $coluna['login'];
    if($coluna['login'] == $_SESSION['login'] && $id_schedule == $_REQUEST['id']){
        $query = "DELETE FROM uschedule WHERE id_schedule = '$id_schedule' AND login = '$login'";
        $result = mysql_query($query) or die (mysql_error());
        $query = "DELETE FROM notes WHERE id_schedule = '$id_schedule' AND creator = '{$_SESSION['login']}'";
        $result = mysql_query($query) or die (mysql_error());

        if($result){
			if($_SESSION['lang'] == "pt"){echo "<script> alert('Você saiu da agenda!');top.location.href='viewSchedule.php' </script>";} 
			else {echo "<script> alert('You leave the schedule!');top.location.href='viewSchedule.php' </script>";}
            
        }
        else{
			if($_SESSION['lang'] == "pt"){echo "<script> alert('Não é possível sair da agenda!') </script>";} 
			else {echo "<script> alert('Could not leave the schedule!') </script>";}
            
        }
    }
}



/* Inserir ou Deletar Nota ativa */
if(@$_REQUEST['note']!="" && @$_REQUEST['action']!=""){
    //$url = "Location: viewNotes.php?".(@$_REQUEST['id'] != "" ? "id=".$_REQUEST['id'] : "");
    editUnote ($_REQUEST['note'],$_REQUEST['action']);
    // Limpar a Url
    echo "<script> Redirect('viewNotes.php?''.({$_REQUEST['id']} != '.''.' ? id= '.{$_REQUEST['id']} .' : '.''.')) </script>";
    /*
    ob_start();
    header ($url);
    ob_end_flush();
    exit;
    */
}

/* DELETAR MEMBRO */
if(@$_REQUEST['user']!="" && @$_REQUEST['action']!="" && @$_REQUEST['id'] != ""){

    $query = "DELETE FROM uschedule WHERE id_schedule = '{$_REQUEST['id']}' AND login = '{$_REQUEST['user']}' AND'{$_SESSION['login']}' IN (SELECT administrator FROM schedule WHERE id = '{$_REQUEST['id']}')";
    $result = mysql_query($query) or die (mysql_error());
    // Limpar a Url
    echo "<script> Redirect('viewNotes.php?id='.{$_REQUEST['id']}') </script>";
    /*
    ob_start();
    header ("Location: viewNotes.php?id=".$_REQUEST['id']);
    ob_end_flush();
    exit;
    */
    }

?>

    <html lang="pt-br">
    <head>
        <title> <?php echo ($_SESSION['lang'] == 'pt' ? 'Notas/Agendas || PostNotes' : 'Notes/Schedules || PostNotes') ?></title>
        <meta charset="UTF-8"/>
        <link rel="stylesheet" type="text/css" href="_css/menu.css"/>
        <link rel="stylesheet" type="text/css" href="_css/indexstyle.css"/>
        <link rel="stylesheet" type="text/css" href="_css/postit.css"/>
        <link rel="stylesheet" type="text/css" href="_css/janelaModal.css"/>
        <script src="_js/jquery-1.11.1.min.js"></script>
        <script src="_js/jquery.easing.1.3.js"></script>
        <script>

            var memberListSize;
            function resizeFilter () {
                memberListSize = document.getElementById('MemberList').clientHeight;
                showFilter ();
                showMembers ();
            }

            function showFilter () {
                var currentFilter = document.getElementById("currentFilter").value;

                var e = document.getElementById("idFilter");
                var strUser = e.options[e.selectedIndex].value;

                /*
                 if (currentFilter == strUser) {
                 document.getElementById("idSearch").style.display = "none";
                 } else {
                 document.getElementById("idSearch").style.display = "inline";
                 }
                 */

                if (strUser == "T") {
                    document.getElementById("idSearch").style.display = "inline";
                    document.getElementById("TimeFilter").style.borderBottom = "1px dashed rgba(0,0,0,.2)";
                    document.getElementById("TimeFilter").style.height = "65px";
                } else {
                    document.getElementById("TimeFilter").style.height = "0px";
                    setTimeout(function(){
                        document.getElementById("TimeFilter").style.border = "none";
                    },400);
                }
            }

            function showMembers () {

                if (document.getElementById("MemberList").style.height == "0px") {
                    document.getElementById("MemberList").style.borderBottom = "1px dashed rgba(0,0,0,.2)";
                    document.getElementById("MemberList").style.height = memberListSize+"px";
                    document.getElementById("menu_notes_view").style.backgroundColor = "#454545";
                } else {
                    document.getElementById("MemberList").style.height = "0px";
                    document.getElementById("menu_notes_view").style.backgroundColor = "#505050";
                    setTimeout(function(){
                        document.getElementById("MemberList").style.border = "none";
                    },400);
                }
            }

        </script>
    </head>
    <body onload="resizeFilter()">
    <?php
    include("menu.php");
    ?>

    <header id="cabecalho">

    </header>
    <div id="min_size"><div id="center_box">
    <form action="#" method="post" name="Filter">
        <input type="hidden" id="currentFilter" name="currentFilter" value="<?php echo @$_POST['nFilter'] ?>" />

        <div id="menu_notes">
            <div id="menu_notes_interior">
                <ul id="menu_notes_lista">
                    <li class="menu_notes_title">
                        <!-- Titulo baseado no Request do ID: Se tiver, mostra o nome da agenda, se não mostra "Your" -->
                        <?php 
						if($_SESSION['lang'] == "pt"){echo (@$_REQUEST['id'] != "" ? "'".$_SESSION['schedule']."'" : "Suas"); ?> notas: 
						<?php } else {echo (@$_REQUEST['id'] != "" ? "'".$_SESSION['schedule']."'" : "Your"); ?> notes: <?php } ?>
                    </li>
                    <?php
                    if (@$_REQUEST['id'] != "") {
                        ?>
                        <li id="menu_notes_view" class="menu_notes_view" onclick="showMembers()">
                            <label for="menu_notes_view"><?php echo ($_SESSION['lang'] == 'pt' ? 'Ver membros' : 'View members') ?></label>
                        </li>
                    <?php
                    } else {
                        ?>
                        <li id="menu_notes_view" class="menu_notes_view">
                            <a href='viewSchedule.php' class="menu_notes_view">
                                <label for="menu_notes_view"><?php echo ($_SESSION['lang'] == 'pt' ? 'Ver suas agendas' : 'View your schedules') ?></label>
                            </a>
                        </li>
                    <?php
                    }
                    ?>
                    <!--
                <li id="menu_notes_view" class="menu_notes_view" onclick="showMembers()">
                    <label for="menu_notes_view"><?php //echo (@$_REQUEST['id'] != "" ? "View members" : "<a href='viewSchedule.php'>View your schedules</a>"); ?></label>
                </li>
                -->
                    <li class="menu_notes_filtro">
                        <select id="idFilter" name="nFilter" onchange="showFilter()">
                            <option value="" <?php echo (@$_POST['nFilter'] == "" ? "selected" : "") ?>> <?php echo ($_SESSION['lang'] == 'pt' ? 'Mostrar todas as notas' : 'Show all notes') ?> </option>
                            <option value="S" <?php echo (@$_POST['nFilter'] == "S" ? "selected" : "") ?>> <?php echo ($_SESSION['lang'] == 'pt' ? 'Mostrar apenas as notas salvas' : 'Show only saved notes') ?> </option>
                            <option value="C" <?php echo (@$_POST['nFilter'] == "C" ? "selected" : "") ?>> <?php echo ($_SESSION['lang'] == 'pt' ? 'Mostrar apenas as notas criadas por você' : 'Show only your created notes') ?> </option>
                            <option value="T" <?php echo (@$_POST['nFilter'] == "T" ? "selected" : "") ?>> <?php echo ($_SESSION['lang'] == 'pt' ? 'Filtrar por data' : 'Filter by date') ?> </option>
                        </select>
                        <input type="submit" id="idSearch" name="nSearch" value="a" />
                    </li>

                    <li class="menu_notes_create">
                        <?php
                        $verefica = "";
                        if (@$_REQUEST['id'] != "") {
                            $query = "SELECT * FROM schedule WHERE id = '{$_REQUEST['id']}' AND ( administrator = '{$_SESSION['login']}' OR id IN (SELECT id_schedule FROM uschedule WHERE login = '{$_SESSION['login']}' AND rank = 1) OR priv_notes = 0)";
                            $result = mysql_query($query) or die (mysql_error());
                            $verefica = mysql_fetch_assoc($result);

                            if (isset($verefica['id']) && $verefica['id']!="") {
                                ?>
                                <a href="createNotes.php?id_schedule=<?php echo @$_REQUEST['id']; ?>"><input type="button" id="idCreate" name="nCreate" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Criar uma nova nota' : 'Create new note') ?>" /></a>
                            <?php
                            }
                        }
                        ?>
                    </li>

                    <li class="menu_notes_search">
                        <?php if (@$_REQUEST['id'] != "") { ?>
						
							<?php if($_SESSION['lang']=="pt"){?>
							<input type="submit" id="Exit" name="Exit" value="<?php echo (@$verefica['id'] != "" && @$verefica['administrator'] == "{$_SESSION['login']}" ? "DELETAR" : "SAIR" ) ?>" onclick="return confirm('Tem certeza que deseja isso?')"  />
							<?php } else {?>
							<input type="submit" id="Exit" name="Exit" value="<?php echo (@$verefica['id'] != "" && @$verefica['administrator'] == "{$_SESSION['login']}" ? "DELETE" : "LEAVE" ) ?>" onclick="return confirm('Are you sure you want to do this?')"  />
							<?php } ?>
							
                        <?php } ?>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Lista de filtros de pesquisa das notas -->


        <div id="TimeFilter">
            <ul>
                <h1> <?php echo ($_SESSION['lang'] == 'pt' ? 'Filtro de tempo' : 'Time Filter:') ?> </h1>
                <li>
                    <?php echo ($_SESSION['lang'] == 'pt' ? 'De:' : 'From:') ?> <input type="date" id="idDateFilter1" name="nDateFilter1" value="<?php echo date('Y-m-d'); ?>" />
                    <?php echo ($_SESSION['lang'] == 'pt' ? 'Até:' : 'To:') ?> <input type="date" id="idDateFilter2" name="nDateFilter2" value="<?php echo date('Y-m-d'); ?>" />
                </li>
            </ul>
        </div>


        <div id="MemberList">
            <ul id="MemberList_ul">
                <h1> <?php echo ($_SESSION['lang'] == 'pt' ? 'Membros' : 'Members') ?> </h1>
                <?php
                if (@$_REQUEST['id'] != "") {
                    $query = "SELECT * FROM schedule WHERE id = '{$_REQUEST['id']}' and ( administrator = '{$_SESSION['login']}' OR id IN (SELECT id_schedule FROM uschedule WHERE login = '{$_SESSION['login']}' AND rank = 1) )";
                    $result = mysql_query($query) or die (mysql_error());
                    $veref_se_e_mod = mysql_fetch_assoc($result);

                    $query = "SELECT uschedule.* , schedule.administrator AS admin  , schedule.priv_schedule AS priv FROM uschedule INNER JOIN schedule ON uschedule.id_schedule = schedule.id WHERE uschedule.id_schedule = '{$_REQUEST['id']}' ORDER BY rank DESC";
                    $result = mysql_query($query) or die (mysql_error());
                    while ($members = mysql_fetch_array($result)) {
                        ?>
                        <li class="memberlist_user">
                            <?php
                            echo ( @$members['admin'] == $members['login'] ? "<span style='color:green'>Admin</span>" : ( $members['rank'] == 1 ? "<span style='color:green'>Mod</span>" : "" ) )." ".$members['login'];
                            //echo ($members['rank'] == 1 ? ( $members['login'] == $_SESSION['login'] ? "<span style='color:green'>Admin</span>" : "<span style='color:green'>Mod</span>" ) : "")." ".$members['login'];
                            ?>
                            <?php
                            if ($veref_se_e_mod != "") {
                                if ( ($members['login'] != $_SESSION['login']) and ( $members['rank'] == 0 or $members['admin'] == $_SESSION['login'] ) and $verefica['id'] != "" ) {
                                    echo "<a href='viewNotes.php?id=".$_REQUEST['id']."&user=".$members['login']."&action=delete'><input type='button' id='deletMember' name='deletMember' value='x' /></a>";
                                }
                            }

                            $admin = ( ( $members['admin'] == $_SESSION['login'] or ( $_SESSION['login'] == $members['login'] && $members['rank'] == 1 ) ) ? true : false);
                            ?>
                        </li>
                    <?php
                    }
                    if (@$admin) {
                        ?>
                        <a href="searchUser.php?id=<?php echo $_REQUEST['id'] ?>"><li class="memberlist_user"><?php echo ($_SESSION['lang'] == 'pt' ? 'Adicionar Membro' : 'Add member') ?></li></a>

                    <?php
                    }

                }


                ?>
            </ul>
        </div>

    </form>

    <div style="width: 810px; margin: 5px auto 0 auto; padding: 5px; border: 1px solid #404040; border-radius: 5px; font-size: 14pt;">
        <div style="display: table-cell; height: 30px; width: 100px; vertical-align: middle; text-align: center"><?php echo ($_SESSION['lang'] == 'pt' ? "Legenda:" : "Subtitle:"); ?></div>
        <div style="display:table-cell; width: 30px; height: 30px; background-color: #d65c48; border: none; border-radius: 5px;"></div> <div style="display: table-cell; width: 150px; height: 30px; vertical-align: middle;"><?php echo ($_SESSION['lang'] == 'pt' ? "- Nota disponível" : "- Available notes"); ?></div>
        <div style="display:table-cell; width: 30px; height: 30px; background-color: #3eaa42; border: none; border-radius: 5px;"></div> <div style="display: table-cell; width: 150px; height: 30px; vertical-align: middle;"><?php echo ($_SESSION['lang'] == 'pt' ? "- Nota adicionada" : "- Added notes"); ?></div>
    </div>
    <div id="notes_group">
        <ul>
            <?php
            /* Se Houver um ID na Url, haverá esse processo */

            /* Procura todas agendas do usuário da sessão */
            $query = " SELECT schedule.* FROM schedule INNER JOIN uschedule ON schedule.id = uschedule.id_schedule WHERE uschedule.login LIKE '{$_SESSION['login']}' ";
            $result = mysql_query($query) or die (mysql_error());
            $i = 0; while ($coluna = mysql_fetch_array($result)) { $user_schedules[$i]=$coluna['id']; $i ++; } // Salva essas agendas em um Array

            /* Procura todas as notas que estejam ativas ao usuário (Salvas na tabela "unotes") */
            $user_notes[0] = "";
            $query = "SELECT notes.* , unotes.login AS login FROM notes INNER JOIN unotes ON notes.id_notes = unotes.id_notes WHERE unotes.login = '{$_SESSION['login']}'";
            $result = mysql_query($query) or die (mysql_error());
            $i = 0; while ($coluna = mysql_fetch_array($result)) { $user_notes[$i]=$coluna['id_notes']; $i ++; } // Salva todos os dados em um Array

            /* Pegar fuso */
            $query = "SELECT time_zone.* FROM time_zone INNER JOIN user ON time_zone.id_timezone = user.id_timezone WHERE user.login LIKE '{$_SESSION['login']}'";
            $result = mysql_query($query) or die (mysql_error());
            $coluna = mysql_fetch_assoc($result);
            $timezone = -$coluna['utc'];

            /* Procura todas as notas salvas */
            $veref_notes_in_schedule = false;
            $query = "SELECT * FROM notes";
            $query .= (@$_REQUEST['id'] != "" ? " WHERE id_schedule = '{$_REQUEST['id']}'" : " WHERE id_notes > 0"); // Se houver ID na Url, filtra apenas para as notas dessa agenda
            if (@$_POST['nFilter'] == "T") {
                $data_inicio = $_POST['nDateFilter1']." 00:00:00";
                $data_final = $_POST['nDateFilter2']." 23:59:59";

                if ( validateDate($data_inicio) && validateDate($data_final) ) { // Verefica os 2 campos de data
                    if( strtotime( $_POST['nDateFilter1'] ) >= strtotime( $_POST['nDateFilter2'] ) ) { // Verefica se a segunda data é maior ou igual
                        $data_inicio = $_POST['nDateFilter1']." 00:00:00";
                        $data_inicio = date('Y-m-d H:i:s', strtotime($timezone." hours",strtotime($data_inicio)));
                        $data_final = date('Y-m-d H:i:s', strtotime("+1439 minutes",strtotime($data_inicio)));
                    } else {
                        $data_inicio = $_POST['nDateFilter1']." 00:00:00";
                        $data_inicio = date('Y-m-d H:i:s', strtotime($timezone." hours",strtotime($data_inicio)));
                        $data_final = $_POST['nDateFilter2']." 23:59:59";
                        $data_final = date('Y-m-d H:i:s', strtotime($timezone." hours",strtotime($data_final)));
                    }
                    $query .= (@$_POST['nFilter'] == "T" ? " AND date between '$data_inicio' and '$data_final'" : ""); // Filtro de tempo
                }
            }
            $query .= (@$_POST['nFilter'] == "C" ? " AND creator LIKE '{$_SESSION['login']}'" : ""); // Filtro de adm
            $query .= (@$_POST['nFilter'] == "S" ? " AND id_notes IN ( SELECT id_notes FROM unotes WHERE login = '{$_SESSION['login']}' ) " : ""); // Filtro de notas salvas

            $result = mysql_query($query) or die (mysql_error());
            $i = 0; while ($coluna = mysql_fetch_array($result)) { $veref_notes_in_schedule=true;$i ++; } // Verefica se retorna algum dado

            /* INICIO DE IMPRESSÃO DAS NOTAS */
            if ( !$veref_notes_in_schedule ) { // Se não houver notas para a agenda (sem o filtro sempre irá voltar algo de dado, a menos que não haja nada no banco) avisa o usuario
                echo "<p id='empty'>";
					if($_SESSION['lang'] == "pt"){echo (@$_POST['nFilter'] == "" ? "Sem notas." : "Não encotrado nenhuma nota." );} 
					else {echo (@$_POST['nFilter'] == "" ? "There is no notes." : "Could not find any note." );}
                echo "</p>";
            } else if ( sizeof(@$user_schedules) < 1 || !isset($user_schedules) ) {
					if($_SESSION['lang'] == "pt"){echo "<p id='empty'> Você não possui agendas. </p>";} else {echo "<p id='empty'> You don't have schedules. </p>";}
                //} else if ( $veref_notes_in_schedule ) {
                //    echo "<p id='empty'> You don't have notes in your schedules. </p>";
            } else {
                $query = "SELECT time_zone.* FROM time_zone INNER JOIN user ON time_zone.id_timezone = user.id_timezone WHERE user.login LIKE '{$_SESSION['login']}'";
                $result = mysql_query($query) or die (mysql_error());
                $coluna = mysql_fetch_assoc($result);
                $timezone = -$coluna['utc'];

                $query = "SELECT * FROM notes WHERE ";
                $query .= (@$_REQUEST['id'] != "" ? " id_schedule = '{$_REQUEST['id']}'" : " id_notes > 0"); // Se houver ID na Url, filtra apenas para as notas dessa agenda
                if (@$_POST['nFilter'] == "T") {
                    $data_inicio = $_POST['nDateFilter1']." 00:00:00";
                    $data_final = $_POST['nDateFilter2']." 23:59:59";

                    if ( validateDate($data_inicio) && validateDate($data_final) ) { // Verefica os 2 campos de data
                        if( strtotime( $_POST['nDateFilter1'] ) >= strtotime( $_POST['nDateFilter2'] ) ) { // Verefica se a segunda data é maior ou igual
                            $data_inicio = $_POST['nDateFilter1']." 00:00:00";
                            $data_inicio = date('Y-m-d H:i:s', strtotime($timezone." hours",strtotime($data_inicio)));
                            $data_final = date('Y-m-d H:i:s', strtotime("+1439 minutes",strtotime($data_inicio)));
                        } else {
                            $data_inicio = $_POST['nDateFilter1']." 00:00:00";
                            $data_inicio = date('Y-m-d H:i:s', strtotime($timezone." hours",strtotime($data_inicio)));
                            $data_final = $_POST['nDateFilter2']." 23:59:59";
                            $data_final = date('Y-m-d H:i:s', strtotime($timezone." hours",strtotime($data_final)));
                        }
                        $query .= (@$_POST['nFilter'] == "T" ? " AND date between '$data_inicio' and '$data_final'" : ""); // Filtro de tempo
                    }
                }
                $query .= (@$_POST['nFilter'] == "C" ? " AND creator LIKE '{$_SESSION['login']}'" : ""); // Filtro de adm
                $query .= (@$_POST['nFilter'] == "S" ? " AND id_notes IN ( SELECT id_notes FROM unotes WHERE login = '{$_SESSION['login']}' ) " : ""); // Filtro de notas salvas
                $query .= " ORDER BY date";
                $result = mysql_query($query) or die (mysql_error());
                while($coluna = mysql_fetch_array($result)) {

                    /* Verefica se a agenda da nota da leitura faz parte das agendas que o usuário está */
                    if (in_array($coluna['id_schedule'],$user_schedules)) {
                        ?>
                        <form action="viewNotes.php" method="post" name="change_alarm" id="formalarm<?php echo $coluna['id_notes']; ?>">
                            <!-- Janela Modal -->
                            <div id="janelaModalAlarm<?php echo $coluna['id_notes']; ?>" class="janelaModalAlarm">
                                <div id="janelaModalAlarm_titulo"><p><?php echo ($_SESSION['lang']=="pt" ? "Editar Alarme" : "Edit Alarm"); ?><input type="button" id="idFecharJanela" name="fecharJanela" value="x" /></p></div>
                                <div id="janelaModalAlarm_corpo">
                                    <!-- Inicio do formulário de envio AJAX - EDITAR ALARME -->
                                    <?php
                                    $pedacos_date = explode(" ", date('Y-m-d H:i:s', strtotime(-$timezone." hours",strtotime($coluna['date']))) );

                                    $date = explode("-",$pedacos_date[0]);
                                    $time = explode(":",$pedacos_date[1]);

                                    $texto_data_final = ($_SESSION['lang']=="pt" ? "Horário limite: ".$time[0].":".$time[1]."<br />".$date[2]."/".$date[1]."/".$date[0] : "Limit time: ".$time[0].":".$time[1]."<br />".$date[0]."/".$date[1]."/".$date[2]);

                                    $query = "SELECT alarm FROM unotes WHERE login LIKE '{$_SESSION['login']}' AND id_notes = '{$coluna['id_notes']}'";
                                    $result_alarm = mysql_query($query) or die (mysql_error());
                                    $alarme = mysql_fetch_assoc($result_alarm);

                                    $pedacos_date = explode(" ", date('Y-m-d H:i:s', strtotime(-$timezone." hours",strtotime($alarme['alarm']))) );

                                    $date = explode("-",$pedacos_date[0]);
                                    $time = explode(":",$pedacos_date[1]);

                                    $texto_data_alarme = ($_SESSION['lang']=="pt" ? "Alarme atual: ".$time[0].":".$time[1]." ".$date[2]."/".$date[1]."/".$date[0] : "Current alarm: ".$time[0].":".$time[1]." ".$date[0]."/".$date[1]."/".$date[2]);

                                    ?>
                                    <p>
                                        <?php echo ($_SESSION['lang']=="pt" ? "Novo alarme: " : "New alarm: "); ?>
                                        <input type="time" id="timeAlarme<?php echo $coluna['id_notes']; ?>" name="Alarme<?php echo $coluna['id_notes']; ?>" value=""/>
                                        <input type="date" id="dateAlarme<?php echo $coluna['id_notes']; ?>" name="Alarme<?php echo $coluna['id_notes']; ?>" value="<?php echo date('Y-m-d',strtotime(-$timezone." hours",strtotime($coluna['date']))); ?>"/>
                                    </p>
                                    <p> <input type="text" id="alarmeAtual<?php echo $coluna['id_notes']; ?>" name="alarmeAtual" value="<?php echo $texto_data_alarme;  ?> " readonly /> </p>
                                    <p> <?php $texto_data_final_edit = str_replace("<br />" , " " , $texto_data_final); echo $texto_data_final_edit;  ?> </p>
                                    <p> <input type="submit" id="novoAlarm<?php echo $coluna['id_notes']; ?>" name="novoAlarm<?php echo $coluna['id_notes']; ?>" value="<?php echo ($_SESSION['lang']=="pt" ? "Salvar" : "Save"); ?>"/> </p>
                                    <!-- Fim editar alarme -->
                                </div>
                            </div>
                            <div id="fundoModalAlarm<?php echo $coluna['id_notes']; ?>" class="fundoModalAlarm"></div>
                            <!-- Fim Janela Modal -->
                        </form>
                        <script>

                            $("#formalarm<?php echo $coluna['id_notes']; ?>").submit(function(e){
                                e.preventDefault();

                                if($("#novoAlarm<?php echo $coluna['id_notes']; ?>").val() == "<?php echo ($_SESSION['lang']=="pt" ? "Salvando...": "Saving..."); ?>") {
                                    return(false);
                                }
                                $("#novoAlarm<?php echo $coluna['id_notes']; ?>").val("<?php echo ($_SESSION['lang']=="pt" ? "Salvando...": "Saving..."); ?>");
                                alert("teste");

                                $.ajax({
                                    url: 'ajaxUnotes.php',
                                    type: 'post',
                                    dataType: 'html',
                                    data: {
                                        'metodo' : 'updatealarm',
                                        'id-note' : "<?php echo $coluna['id_notes']; ?>",
                                        'new-alarm-time' : $("#timeAlarme<?php echo $coluna['id_notes']; ?>").val(),
                                        'new-alarm-date' : $("#dateAlarme<?php echo $coluna['id_notes']; ?>").val()
                                    }
                                }).done(function(data){
                                        $("#novoAlarm<?php echo $coluna['id_notes']; ?>").val("<?php echo ($_SESSION['lang']=="pt" ? "Salvar" : "Save"); ?>");

                                        var dados = data.split("-OLHAQUEBRA-")
                                        var texto = dados[0];

                                        if ( texto != "<?php echo ($_SESSION['lang']=="pt" ? "Alarme inválido" : "Invalid alarm") ?>" ) {
                                            $("#alarmeAtual<?php echo $coluna['id_notes']; ?>").val(dados[1]);
                                        }

                                    });
                            });

                            // Janela de alarmes
                            $(document).ready(function() {
                                $(".fundoModalAlarm, .janelaModalAlarm").hide();

                                $("#fundoModalAlarm<?php echo $coluna['id_notes']; ?>,#idFecharJanela").click(function(){

                                    $("#fundoModalAlarm<?php echo $coluna['id_notes']; ?>, #janelaModalAlarm<?php echo $coluna['id_notes']; ?>").fadeOut();

                                });

                                $("#abrirAlarme<?php echo $coluna['id_notes']; ?>").click(function(){

                                    $("#fundoModalAlarm<?php echo $coluna['id_notes']; ?>, #janelaModalAlarm<?php echo $coluna['id_notes']; ?>").fadeIn();

                                });

                            });


                        </script>
                        <form action="viewNotes.php" method="post" name="change_notes" id="formnota<?php echo $coluna['id_notes']; ?>">
                            <li id="notes_body"><ul>
                                    <li id="notes_button">
                                        <!-- Componentes com dados para o envio via AJAX -->
                                        <input type="submit" id="ChangeNote<?php echo $coluna['id_notes']; ?>" name="ChangeNote<?php echo $coluna['id_notes']; ?>" value="<?php echo (in_array($coluna['id_notes'],$user_notes) ? "x" : "+"); ?>" />
                                        <input type="hidden" id="metodo<?php echo $coluna['id_notes']; ?>" value="formulario-ajax" />
                                        <input type="hidden" id="id-note<?php echo $coluna['id_notes']; ?>" value="<?php echo $coluna['id_notes']; ?>" />
                                        <input type="hidden" id="id-schedule<?php echo $coluna['id_notes']; ?>" value="<?php echo @$_REQUEST['id']?>" />
                                        <!-- Fim dados -->
                                        <script>

                                            $("#formnota<?php echo $coluna['id_notes']; ?>").submit(function(e){
                                                e.preventDefault();

                                                if($("#ChangeNote<?php echo $coluna['id_notes']; ?>").val() == '...'){
                                                    return(false);
                                                }

                                                $("#ChangeNote<?php echo $coluna['id_notes']; ?>").val('...');

                                                $.ajax({
                                                    url: 'ajaxUnotes.php',
                                                    type: 'post',
                                                    dataType: 'html',
                                                    data: {
                                                        'metodo' : $('#metodo<?php echo $coluna['id_notes']; ?>').val(),
                                                        'id-note' : $('#id-note<?php echo $coluna['id_notes']; ?>').val(),
                                                        'id-schedule' : $('#id-schedule<?php echo $coluna['id_notes']; ?>').val()
                                                    }
                                                }).done(function(data) {

                                                    var dados = data.split("-OLHAQUEBRA-")
                                                    var texto = dados[0];

                                                    if (texto == 'del') {
                                                        $("#ChangeNote<?php echo $coluna['id_notes']; ?>").val('+');
                                                    } else if (texto == 'add') {
                                                        $("#ChangeNote<?php echo $coluna['id_notes']; ?>").val('x');
                                                        $("#alarmeAtual<?php echo $coluna['id_notes']; ?>").val(dados[1]);
                                                    } else if (texto == 'erro') {
                                                        alert('Erro!');
                                                        $("#ChangeNote<?php echo $coluna['id_notes']; ?>").val('o');
                                                    } else {
                                                        $("#ChangeNote<?php echo $coluna['id_notes']; ?>").val('o');
                                                    }

                                                    if ($("#ChangeNote<?php echo $coluna['id_notes']; ?>").val() == '...') {
                                                        $("#ChangeNote<?php echo $coluna['id_notes']; ?>").val('o');
                                                    }

                                                    if ($("#ChangeNote<?php echo $coluna['id_notes']; ?>").val() == 'x') {
                                                        $("#abrirAlarme<?php echo $coluna['id_notes']; ?>").show();
                                                    } else {
                                                        $("#abrirAlarme<?php echo $coluna['id_notes']; ?>").hide();
                                                    }
                                                });
                                            })
                                        </script>
                                    </li>
                                    <li id="notes_text"><?php echo $coluna['annotation']; ?></li>
                                    <li id="notes_creator"><?php echo ($_SESSION['lang'] == 'pt' ? 'De:' : 'By:') ?> <?php echo $coluna['creator']; ?></li>
                                    <li id="notes_time">
                                        <p><?php echo $texto_data_final; ?></p>

                                        <p><input type="button" id="abrirAlarme<?php echo $coluna['id_notes']; ?>" name="abrirAlarme<?php echo $coluna['id_notes']; ?>" value="<?php echo ($_SESSION['lang']=="pt" ? "editar alarme" : "edit alarm")?>"/></p>
                                        <?php
                                        //Controla Button Edit
                                        ?>
                                        <script>
                                            $(document).ready(function(){
                                                if ($("#ChangeNote<?php echo $coluna['id_notes']; ?>").val() == 'x') {
                                                    $("#abrirAlarme<?php echo $coluna['id_notes']; ?>").show();
                                                } else {
                                                    $("#abrirAlarme<?php echo $coluna['id_notes']; ?>").hide();
                                                }
                                            });
                                        </script>
                                    </li>
                                    <li id="notes_delet">
                                        <?php
                                        $query = "SELECT * FROM notes WHERE id_notes = '{$coluna['id_notes']}' AND ( creator = '{$_SESSION['login']}' OR id_schedule IN ( SELECT schedule.id FROM schedule INNER JOIN uschedule ON schedule.id = uschedule.id_schedule WHERE ( uschedule.login = '{$_SESSION['login']}' AND uschedule.rank = 1 ) OR schedule.administrator = '{$_SESSION['login']}' ) )";
                                        $result2 = mysql_query($query) or die (mysql_error());
                                        while ($delete = mysql_fetch_array($result2)) {
                                            ?>
                                            <a href="viewNotes.php?<?php echo (@$_REQUEST['id'] != "" ? "id=".$_REQUEST['id']."&" : "") ?>note=<?php echo $coluna['id_notes']; ?>&action=delete">
                                                <input type="button" id="idDeleteNote" name="button" value="x" />
                                            </a>
                                        <?php
                                        }
                                        ?>
                                    </li>
                                </ul></li>
                        </form>
                    <?php
                    }

                }
            }
            /* FIM DA IMPRESSÃO */

            ?>
        </ul>
    </div>

    </div></div>
    <?php require("footer.php"); ?>

    </body>
    </html>
<?php
unset($_SESSION['schedule']); // Limpa o nome da agenda para evitar continuar salvo em interrupção inesperada do sistema
?>