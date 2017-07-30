<?php
include ('config.php');
//include ('function.php');

@session_start();

/* Verefica o stats da página(Qual página de edição está). */
$stats = @$_POST['Stats'];
/* Verefica se algum botão foi clicado, e atualiza o stats baseado nele. */
if (@$_REQUEST['changeStats']=="Edit your information" || @$_REQUEST['changeStats']=="Editar informações") {
    $stats = 1; // Editar informações gerais.
} else if (@$_REQUEST['changeStats']=="Edit your password" || @$_REQUEST['changeStats']=="Editar senha") {
    $stats = 2; // Editar sua senha.
} else if (@$_REQUEST['changeStats']=="Edit your time zone" || @$_REQUEST['changeStats']=="Editar fuso horário"){
    $stats = 3; // Editar seu fuso horário.
}
$entrar_força = false; // Deixa a entrada forçada de atualização de dados falsa.

/* Verefica se há um stats atual setado */
if (isset($_SESSION['stats_atual'])) {
    $_POST['Stats'] = $_SESSION['stats_atual'];
    $stats = @$_POST['Stats'];
    $entrar_força = true;
    unset($_SESSION['stats_atual']);
}

if( (@$_SESSION['login'] && (@$_REQUEST['changeStats'] != "") || @$entrar_força) ){
    $login = $_SESSION['login'];
    $select = "SELECT * FROM user WHERE '$login' LIKE login";
    $result_select = mysql_query($select) or die (mysql_error());
    while ($coluna = mysql_fetch_assoc($result_select)){
        $_POST['nName'] = $coluna['name'];
        $_POST['nSex'] = $coluna['gender'];
        $_POST['nNasc'] = $coluna['birth'];
        $_POST['nEmail'] = $coluna['email'];
        $_POST['nFuso'] = $coluna['id_timezone'];
        $_SESSION['alert'] = $coluna['alert_time'];
        $_SESSION['password'] = $coluna['pass'];
    }
}

/* Verefica se o botão de salvar foi apertado. */
if(@$_REQUEST['button']=="Save" || @$_REQUEST['button']=="Salvar"){
    //if ( verefFields ($_POST['nUser'],$_POST['nPass3'],$_POST['nPass3'],$_POST['nEmail'],$_POST['nSex'],$_POST['nName'],$_POST['nNasc'],$_POST['nFuso']) ) {
        $update = ""; // Limpa o update.

        if ($_POST['Stats'] == 1) { // Se o stats for 1, atualiza informações básicas
            if (verefNull($_POST['nName'])) { // Vereficador de Nome
                if (verefNull($_POST['nSex'])) { // Vereficador de Sexo
                    if (verefNull($_POST['nNasc']) && verefNasc($_POST['nNasc'])) { // Vereficador de Nascimento
                        if (verefEmail($_POST['nEmail'])) { // Vereficador de E-Mail
                            /* Update de dados */
                            $update = "UPDATE user SET name = '{$_POST['nName']}' , gender = '{$_POST['nSex']}' , birth = '{$_POST['nNasc']}' , email = '{$_POST['nEmail']}' , alert_time = '{$_POST['nAlert']}' WHERE login LIKE '{$_SESSION['login']}' ";
                            if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Alterações realizadas com sucesso!";} else {$_SESSION['mensagem'] = "Successful changes!";} // Mensagem de sucesso
                        } else {
                            if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "E-mail invalido!";} else {$_SESSION['mensagem'] = "Invalid e-mail!";} // Mensagem de erro
                        }
                    } else {
                        if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Escolha uma data de nascimento!";} else {$_SESSION['mensagem'] = "Choose a date of birth!";} // Mensagem de erro
                    }
                } else {
                    if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Escolha um sexo!";} else {$_SESSION['mensagem'] = "Choose a gender!";} // Mensagem de erro
                }
            } else {
                if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Escolha um nome!";} else {$_SESSION['mensagem'] = "Choose a name!";} // Mensagem de erro
            }
        } else if ($_POST['Stats'] == 2) { // Se o stats for 2, atualiza sua senha
            if (verefPass($_POST['nPass2']) == "") { // Verefica se há senha
                if ($_POST['nPass2'] == $_POST['nPass3']) { // Verefica se as senhas são iguais
                    if (encrypt($_POST['nPass1']) == $_SESSION['password']) { // Verefica se a senha atual é compatível
                        $passnovaerasenhawordnew = encrypt($_POST['nPass2']); // Criptografa
                        /* Atualiza sua senha */
                        $update = "UPDATE user SET pass = '$passnovaerasenhawordnew' WHERE login LIKE '{$_SESSION['login']}'";
                        if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Senha alterada com sucesso!";} else {$_SESSION['mensagem'] = "Password changed successfully!";} // Mensagem de sucesso
                    } else {
                        if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Senha incorreta!";} else {$_SESSION['mensagem'] = "Wrong password!";} // Mensagem de erro
                    }
                } else {
                    if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Nova senha diferente!";} else {$_SESSION['mensagem'] = "New password diferent!";} // Mensagem de erro
                }
            } else {
                if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Nova senha inválida!";} else {$_SESSION['mensagem'] = "New password invalid!";} // Mensagem de erro
            }
        } else if ($_POST['Stats'] == 3) { // Se o stats for 3, atualiza seu fuso horário
            if (verefNull($_POST['nFuso'])) { // Verefica se tem um fuso selecionado
                /* Atualiza fuso horário */
                $update = "UPDATE user SET id_timezone = '{$_POST['nFuso']}' WHERE login LIKE '{$_SESSION['login']}'";
                if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Fuso horário alterado com sucesso!";} else {$_SESSION['mensagem'] = "Time zone changed successfully!";} // Mensagem de sucesso
            } else {
                if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Escolha um fuso horário!";} else {$_SESSION['mensagem'] = "Choose a time zone!";} // Mensagem de erro
            }

        }
    /* Verefica se há algo para sofrer update */
    if ($update != "") {
        $result_update = mysql_query($update) or die (mysql_error());
        /* Se não inserir */
        if (!$result_update) {
            if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Erro!";} else {$_SESSION['mensagem'] = "Error!";} // Troca mensagem de erro ou sucesso.

            header ("Location: editProfile.php");
            exit;

        }
        $_SESSION['stats_atual'] = $_POST['Stats']; // Tira o usuário
        // Limpa Url

        header ("Location: editProfile.php");
        exit;

    }
    $_SESSION['stats_atual'] = $_POST['Stats']; // Salva seu stats atual em sessão
    // Limpa Url

    header ("Location: editProfile.php");
    exit;



        //$result_update = mysql_query($update) or die (mysql_error());
        //if ($result_update) echo "<script> alert('REGISTRO ATUALIZADO COM SUCESSO'); </script>";
    //} else {
    //    header ("Location: editProfile.php");
    //    exit;
    //}
}
if(@$_REQUEST['button']=="Back" || @$_REQUEST['button']=="Voltar"){
    $stats = "";
}


?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title> <?php echo ($_SESSION['lang'] == 'pt' ? 'Editar Perfil || PostNotes' : 'Edit Profile || PostNotes') ?> </title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" type="text/css" href="_css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="_css/indexstyle.css"/>
    <script>
        function PegaPais() {
            var teste = document.getElementById("idPais").value;
            document.form1.action = "EditProfile.php?pag=editProfile.php&id_selected="+teste;
            document.form1.submit();
        }

        function ClearAllFields () {

            document.getElementById("idUser").value = "";
            document.getElementById("idName").value = "";
            document.getElementById("idPass").value = "";
            document.getElementById("idEmail").value = "";
            document.getElementById("idSexM").checked = false;
            document.getElementById("idSexF").checked = false;
            document.getElementById("idNasc").value = "";
            document.getElementById("idPais").value = "";
            document.getElementById("idFuso").value = "";

            //setCheckedValue(document.forms['form1'].elements['nSex'],'');

        }
        function setCheckedValue(radioObj, newValue) {
            if(!radioObj)
                return;
            var radioLength = radioObj.length;
            if(radioLength == undefined) {
                radioObj.checked = (radioObj.value == newValue.toString());
                return;
            }
            for(var i = 0; i < radioLength; i++) {
                radioObj[i].checked = false;
                if(radioObj[i].value == newValue.toString()) {
                    radioObj[i].checked = true;
                }
            }
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
    <form action="#" method="POST" name="form1">
    <input type="hidden" id="idStats" name="Stats" value="<?php echo $stats; ?>" readonly/>


<!-- STATS MACHINE START -->
<?php



    if (@$stats == "") {
        // MENU: EDITS OPTIONS

?>
    <fieldset>
        <legend><?php echo ($_SESSION['lang'] == 'pt' ? 'Editar Perfil' : 'Edit your profile:') ?></legend>

        <p><input type="submit" id="idInfoB" name="changeStats" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Editar informações' : 'Edit your information') ?>"/></p>
        <p><input type="submit" id="idPassB" name="changeStats" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Editar senha' : 'Edit your password') ?>"/></p>
        <p><input type="submit" id="idTimeB" name="changeStats" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Editar fuso horário' : 'Edit your time zone') ?>"/></p>
    </fieldset>
<?php

    } else {

        /*
        1 - Change infos
        2 - Change pass
        3 - Change time
        */
    switch ($stats) {
        case 1: {

?>
    <table>
        <tr>
            <th> <label for="idName"><?php echo ($_SESSION['lang'] == 'pt' ? 'Nome:' : 'Name:') ?></label> </th>
            <td> <input type="text" id="idName" name="nName" value="<?php echo @$_POST['nName']; ?>" placeholder="<?php echo ($_SESSION['lang'] == 'pt' ? 'Digite seu nome' : 'Enter your name.') ?>" maxlength="50" size="20"/>  </td>
        </tr>
        <tr>
            <th> <label for="idEmail">E-mail:</label> </th>
            <td> <input type="email" id="idEmail" name="nEmail" value="<?php echo @$_POST['nEmail']; ?>" placeholder="<?php echo ($_SESSION['lang'] == 'pt' ? 'Digite seu e-mail' : 'Enter your email.') ?>" maxlength="30" size="20"/>  </td>
        </tr>
        <tr>
            <th> <label for="idNasc"><?php echo ($_SESSION['lang'] == 'pt' ? 'Data de nascimento:' : 'Birth:') ?></label> </th>
            <td> <input type="date" id="idNasc" name="nNasc" value="<?php echo @$_POST['nNasc']; ?>"/>  </td>
        </tr>
        <tr>
            <th> <?php echo ($_SESSION['lang'] == 'pt' ? 'Sexo:' : 'Gender:') ?> </th>
            <td>
                <input type="radio" id="idSexM" name="nSex" value="m" <?php echo (@$_POST['nSex']=='m'?"checked":""); ?>/> <label for="idSexM"><?php echo ($_SESSION['lang'] == 'pt' ? 'Masculino' : 'Male') ?></label>
                <input type="radio" id="idSexF" name="nSex" value="f" <?php echo (@$_POST['nSex']=='f'?"checked":""); ?>/> <label for="idSexF"><?php echo ($_SESSION['lang'] == 'pt' ? 'Feminino' : 'Female') ?></label>
            </td>
        </tr>
        <tr>
            <th> <?php echo ($_SESSION['lang'] == 'pt' ? 'Alerta padrão:' : 'Default alert:') ?> </th>
            <td>
                <?php
                $alert = $_SESSION['alert'];
                @$alert = ($alert == ""? 60 : $_SESSION['alert']);
                ?>
                <select id="idAlert" name="nAlert">
                    <option value="0" disabled style="display: none;" > <?php echo ($_SESSION['lang'] == 'pt' ? 'Selecione um valor.' : 'Select a value.') ?> </option>
                    <option value="1440" <?php echo ($alert==1440?"selected":""); ?>> <?php echo ($_SESSION['lang'] == 'pt' ? '1 dia' : '1 day') ?> </option>
                    <option value="720" <?php echo ($alert==720?"selected":""); ?>> <?php echo ($_SESSION['lang'] == 'pt' ? '12 horas' : '12 hours') ?> </option>
                    <option value="360" <?php echo ($alert==360?"selected":""); ?>> <?php echo ($_SESSION['lang'] == 'pt' ? '6 horas' : '6 hours') ?> </option>
                    <option value="180" <?php echo ($alert==180?"selected":""); ?>> <?php echo ($_SESSION['lang'] == 'pt' ? '3 horas' : '3 hours') ?> </option>
                    <option value="60" <?php echo ($alert==60?"selected":""); ?>> <?php echo ($_SESSION['lang'] == 'pt' ? '1 hora' : '1 hour') ?> </option>
                    <option value="30" <?php echo ($alert==30?"selected":""); ?>> <?php echo ($_SESSION['lang'] == 'pt' ? '30 minutos' : '30 minutes') ?> </option>
                    <option value="15" <?php echo ($alert==15?"selected":""); ?>> <?php echo ($_SESSION['lang'] == 'pt' ? '15 minutos' : '15 minutes') ?> </option>
                    <option value="5" <?php echo ($alert==5?"selected":""); ?>> <?php echo ($_SESSION['lang'] == 'pt' ? '5 minutos' : '5 minutes') ?> </option>
                </select>
            </td>
        </tr>
    </table>
<?php

            break;
        }
        case 2: {

?>
    <table>
        <tr>
            <th> <label for="idPass"><?php echo ($_SESSION['lang'] == 'pt' ? 'Senha atual:' : 'Current password:') ?></label> </th>
            <td> <input type="password" id="idPass" name="nPass1" value="<?php echo @$_POST['nPass']; ?>" placeholder="<?php echo ($_SESSION['lang'] == 'pt' ? 'Digite sua senha' : 'Type your password') ?>" maxlength="20" size="20"/> </td>
        </tr>
        <tr>
            <th> <label for="idPass2"><?php echo ($_SESSION['lang'] == 'pt' ? 'Nova senha:' : 'New password:') ?></label> </th>
            <td> <input type="password" id="idPass3" name="nPass3" value="<?php echo @$_POST['nPass3']; ?>" placeholder="<?php echo ($_SESSION['lang'] == 'pt' ? 'Escolha uma senha' : 'Choose a password') ?>" maxlength="20" size="20"/> </td>
        </tr>
        <tr>
            <th> <label for="idPass2"><?php echo ($_SESSION['lang'] == 'pt' ? 'Confirmar nova senha:' : 'Confirm new password:') ?></label> </th>
            <td> <input type="password" id="idPass2" name="nPass2" value="<?php echo @$_POST['nPass2']; ?>" placeholder="<?php echo ($_SESSION['lang'] == 'pt' ? 'Confirme a senha' : 'Confirm password') ?>" maxlength="20" size="20"/> </td>
        </tr>
    </table>
<?php

            break;
        }
        case 3: {

?>
    <table>
        <tr>
            <th> <label for="idFuso"><?php echo ($_SESSION['lang'] == 'pt' ? 'Fuso horário:' : 'Time zone:') ?></label> </th>
            <td> <?php

                $query = "SELECT id_timezone,time_zone FROM time_zone ORDER BY id_timezone";
                $result = mysql_query($query) or die (mysql_error());

                ?>
                <select  id="idFuso" name="nFuso">
                    <option value="" <?php echo "" == @$id_fuso?"selected":"" ?> disabled style="display: none;"> ..:: <?php echo ($_SESSION['lang'] == 'pt' ? 'Escolha o fuso horário' : 'Choose the time zone') ?> ::.. </option>
                    <?php
                    while( $row = mysql_fetch_assoc($result) )
                    {
                        ?>
                        <option value="<?php echo $row['id_timezone']; ?>"  <?php echo $row['id_timezone'] == @$_POST['nFuso']?"selected":"" ?> ><?php echo @$row['time_zone'] ?></option>
                    <?php
                    }
                    ?>
                </select>
            </td>
        </tr>
    </table>

<?php

            break;
        }
        default: {
            break;
        }
    }


    ?>
        <fieldset>
            <p><input type="submit" id="idSave" name="button" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Salvar' : 'Save') ?>"/></p>
            <p><input type="submit" id="idBack" name="button" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Voltar' : 'Back') ?>"/></p>
        </fieldset>
    <?php
    }
?>
<!-- STATS MACHINE END -->


    </form>
</div>
</div>

<?php require("footer.php"); ?>

</body>
</html>