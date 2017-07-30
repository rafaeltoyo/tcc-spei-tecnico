<!DOCTYPE html>
<?php
include('config.php');
//include('function.php');
require('veref.php');

@session_start();

@$nome = $_POST['nNome'];
@$priv_agenda = $_POST['nPrivacidade'];
@$priv_nota = $_POST['nTipo'];
@$login = $_SESSION['login'];

if (@$_REQUEST['id'] != "" && @$_REQUEST['button']=="") {
    $this_schedule_is_your = false;
    $result = mysql_query( "SELECT * FROM schedule WHERE id = '{$_REQUEST['id']}'" ) or die (mysql_error());
    $_SESSION['mensagem'] = "Schedule doesn't exist!";
    while ($coluna = mysql_fetch_array($result)) {
        if ( $coluna['administrator'] == $_SESSION['login'] ) {
            @$nome = $coluna['name'];
            @$priv_agenda = $coluna['priv_schedule'];
            @$priv_nota = $coluna['priv_notes'];
            $this_schedule_is_your = true;
            break;
        }
        ($_SESSION['lang']=="pt" ? "$_SESSION[mensagem] = Você não está nesta agenda!" : "$_SESSION[mensagem] = You aren't in this schedule!" );
    }
    if (!$this_schedule_is_your) {
        echo "<script> Redirect('viewSchedule.php') </script>";
        /*ob_start();
        header("Location: viewSchedule.php");
        ob_end_flush();
        exit;
        */
    } else {
        unset($_SESSION['mensagem']);
    }
}

if(@$_REQUEST['button']=="Save" || @$_REQUEST['button']=="Salvar"){
        if (@$_REQUEST['id'] != "") {
            $insere = "UPDATE schedule SET administrator = '{$_SESSION['login']}' , name = '$nome' , priv_schedule = '$priv_agenda' , priv_notes = '$priv_nota' WHERE id = '{$_REQUEST['id']}'";
            $result_insere = mysql_query($insere) or die (mysql_error());
				if($_SESSION['lang'] == "pt"){
					if ($result_insere){
					echo "<script> alert ('Agenda alterada com sucesso!!');top.location.href='viewSchedule.php'; </script>";
					exit;
					}
				} else {
					if ($result_insere){
					echo "<script> alert ('Schedule changed successfully!!');top.location.href='viewSchedule.php'; </script>";
					exit;
					}
				}
            
        } else {
            $insere = "INSERT INTO schedule(administrator,name,priv_schedule,priv_notes) VALUES('{$_SESSION['login']}','$nome','$priv_agenda','$priv_nota')";
            $result_insere = mysql_query($insere) or die (mysql_error());
				if($_SESSION['lang'] == "pt"){
					if ($result_insere){
					echo "<script> alert ('Agenda inserida com sucesso!');top.location.href='viewSchedule.php'; </script>";
					exit;
					}
				} else {
					if ($result_insere){
					echo "<script> alert ('Schedule successfully inserted!');top.location.href='viewSchedule.php'; </script>";
					exit;
					}
				}
            
        }
}
?>

<html lang="pt-br">

<head>
    <?php if (@$_REQUEST['id'] != "") {
        ?><title> <?php echo ($_SESSION['lang'] == 'pt' ? 'Editar Agenda || PostNotes' : 'Edit Schedule || PostNotes') ?> </title><?php
    } else {
        ?><title> <?php echo ($_SESSION['lang'] == 'pt' ? 'Criar Agenda || PostNotes' : 'Create Schedule || PostNotes') ?> </title><?php
    }?>


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

        <div id="conteudo">
            <?php
            if (@$_REQUEST['id'] != "") {
            ?>
                <h1><?php echo ($_SESSION['lang'] == 'pt' ? 'Editar Agenda' : 'Edit Schedule') ?></h1>
            <?php
            } else {
            ?>
                <h1><?php echo ($_SESSION['lang'] == 'pt' ? 'Criar Agenda' : 'Create Schedule') ?></h1>
            <?php
            }
            ?>
        </div>

        <form action="#" method="POST" name="form1">
            <table>
                <tr>
                    <th><label for="nameSchedule"><?php echo ($_SESSION['lang'] == 'pt' ? 'Nome:' : 'Name:') ?></label></th>
                    <td><input type="text" name="nNome" id="nameSchedule" value="<?php echo @$nome; ?>" placeholder="<?php echo ($_SESSION['lang'] == 'pt' ? 'Nome da sua agenda' : 'Name of your schedule') ?>" maxlength="20" size="30" />
                    </td>
                </tr>
                <tr>
                    <th><label for="privacitySchedule"><?php echo ($_SESSION['lang'] == 'pt' ? 'Privacidade da agenda:' : 'Privacity of your schedule:') ?></label></th>
                    <td><select id="privacitySchedule" name="nPrivacidade">
                            <option value="" <?php echo ($priv_agenda==""?"selected":"");?> disabled style="display: none;"> ..:: <?php echo ($_SESSION['lang'] == 'pt' ? 'Privacidade da agenda' : 'Privacity of schedule') ?> ::.. </option>
                            <option value="0" <?php echo ($priv_agenda=="0"?"selected":""); ?>><?php echo ($_SESSION['lang'] == 'pt' ? 'Pública' : 'Public') ?></option>
                            <option value="1" <?php echo ($priv_agenda=="1"?"selected":""); ?>><?php echo ($_SESSION['lang'] == 'pt' ? 'Precisa de aprovação' : 'Require approbation') ?></option>
                            <option value="2" <?php echo ($priv_agenda=="2"?"selected":""); ?>><?php echo ($_SESSION['lang'] == 'pt' ? 'Privada' : 'Private') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="typeSchedule"><?php echo ($_SESSION['lang'] == 'pt' ? 'Privacidade das anotações:' : 'Privacity of your notes:') ?></label></th>
                    <td>
                        <select id="typeSchedule" name="nTipo">
                            <option value="" <?php echo ($priv_nota==""?"selected":""); ?> disabled style="display: none;"> ..:: <?php echo ($_SESSION['lang'] == 'pt' ? 'Privacidade das anotações' : 'Privacy of your schedule notes') ?> ::.. </option>
                            <option value="0" <?php echo ($priv_nota=="0"?"selected":""); ?>><?php echo ($_SESSION['lang'] == 'pt' ? 'Qualquer pessoa pode criar anotações' : 'Anyone can create notes.') ?></option>
                            <option value="1" <?php echo ($priv_nota=="1"?"selected":""); ?>><?php echo ($_SESSION['lang'] == 'pt' ? 'Só o administrador pode criar anotações' : 'Only administrator can create notes.') ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="button" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Salvar' : 'Save') ?>" />
                        <input type="button" name="button" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Limpar' : 'Clean') ?>" onclick="ClearAllFields();" />
                        <a href="searchUser.php<?php echo (@$_REQUEST['id'] != "" ? "?id=".$_REQUEST['id'] : "") ?>"><input type="button" name="button" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Voltar' : 'Back') ?>" /></a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>
<?php require("footer.php"); ?>
</div>
</body>
</html>