<?php
include('config.php');
require('veref.php');

@session_start();

@$note = $_POST['nNote'];
@$idSchedule = @$_REQUEST['id_schedule'];

date_default_timezone_set('africa/ouagadougou');

if (@$_REQUEST['id_schedule'] == "") {
    header("Location: viewNotes.php");
    exit;
} else {
    $query = "SELECT * FROM schedule WHERE id = '{$_REQUEST['id_schedule']}' AND ( administrator = '{$_SESSION['login']}' OR id IN (SELECT id_schedule FROM uschedule WHERE login = '{$_SESSION['login']}' AND rank = 1) OR priv_notes = 0 )";
    $result = mysql_query($query) or die (mysql_error());
    $verefica = mysql_fetch_assoc($result);
    if (@$verefica['id'] == "") {
        header("Location: viewNotes.php");
        exit;
    }
}

if(@$_REQUEST['button']=="Save" || @$_REQUEST['button']=="Salvar"){

	@$data = $_POST['nDate'];
	@$hour= $_POST['nTime'];
	@$data_hora = $data . " " . $hour.":00";
	if ( !verefAlarm($data_hora) ) {
		if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Data inválida";} else {$_SESSION['mensagem'] = "Invalid date";}
        header("Location: ".$_SERVER['PHP_SELF'].'?id_schedule='.$_REQUEST['id_schedule']);
        exit;

	} else {
        $query = "SELECT time_zone.* FROM time_zone INNER JOIN user ON time_zone.id_timezone = user.id_timezone WHERE user.login LIKE '{$_SESSION['login']}'";
        $result = mysql_query($query) or die (mysql_error());
        $coluna = mysql_fetch_assoc($result);
        $timezone = -$coluna['utc'];


		$login = $_SESSION['login'];

        $data_nova = date('Y-m-d H:i:s', strtotime($timezone." hours",strtotime($data_hora)));
		$insere = "INSERT INTO notes(id_schedule,annotation,creator,date) VALUES('{$_REQUEST['id_schedule']}','{$_POST['nNote']}','{$_SESSION['login']}','$data_nova')";
		$result = mysql_query($insere) or die (mysql_error());
        editUnote (mysql_insert_id(),"change");
        if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Nota criada!";} else {$_SESSION['mensagem'] = "Note created!";}
        header("Location: viewNotes.php?id=".$_REQUEST['id_schedule']);
        exit;
	}
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title> <?php echo ($_SESSION['lang'] == 'pt' ? 'Criar Anotação || PostNotes' : 'Create Note || PostNotes') ?> </title>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="_css/menu.css">
    <link rel="stylesheet" type="text/css" href="_css/indexstyle.css">
    <script>
        function ClearAllFields () {
            document.getElementById("Note").value = "";
            document.getElementById("Date").value = "";
            document.getElementById("Time").value = "";
        }
    </script>
</head>

<body>
<?php
include ('menu.php');
?>

<header id="cabecalho">

</header>

<div id="min_size">
    <div id="tableRegister">
        <form action="#" method="POST" name="form1">
            <table>
                <tr>
                    <td colspan="2"> <?php echo ($_SESSION['lang'] == 'pt' ? 'Nova Anotação' : 'New Note') ?> </td>
                </tr>
                <tr>
                    <th><label for="Note"> <?php echo ($_SESSION['lang'] == 'pt' ? 'Anotação:' : 'Note:') ?> </label> </th>
                    <td>
					<textarea name="nNote" id="Note" placeholder="<?php echo ($_SESSION['lang'] == 'pt' ? 'Escreva aqui sua anotação!' : 'Write your note here!') ?>" cols="40" rows="3" maxlength="140" required><?php echo @$_POST['nNote'] ?></textarea>
					<!--<input type="text" name="nNote" id="Note" value="<?php echo @$_POST['nNote']; ?>" placeholder="Escreva aqui sua anotação!" maxlength="140" size="40" />-->
					</td>
                </tr>
                <tr>
                    <th> <?php echo ($_SESSION['lang'] == 'pt' ? 'Data:' : 'Date:') ?> </th>
                    <td><input type="date" name="nDate" id="Date" value="<?php echo @$_POST['nDate']; ?>" placeholder="YYYY-MM-DD" required/> </td>
                </tr>
				<tr>
                    <th> <?php echo ($_SESSION['lang'] == 'pt' ? 'Hora:' : 'Hour:') ?> </th>
                    <td><input type="time" name="nTime" id="Time" value="<?php echo @$_POST['nTime']; ?>" placeholder="HH-MM" required/> </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="button" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Salvar' : 'Save') ?>" />
                        <input type="button" name="button" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Limpar' : 'Clean') ?>" onclick="ClearAllFields();" />
                        <a href="viewNotes.php<?php echo (@$_REQUEST['id_schedule']!="" ? "?id=".$_REQUEST['id_schedule'] : "" ) ?>"><input type="button" name="button" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Voltar' : 'Back') ?>" /></a>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php require("footer.php"); ?>
</body>
</html>