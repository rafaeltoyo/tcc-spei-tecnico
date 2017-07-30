<?php
include ("config.php");

if (isset($_SESSION['login']) ) {
    if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Você já possui uma conta!";} else { $_SESSION['mensagem'] = "You already have an account!";}
    ob_start();
    header("Location: index.php");
    ob_end_flush();
    exit;
}

@$login = $_POST['nUser'];
@$senha = $_POST['nPass'];
@$senha2 = $_POST['nPass2'];
@$nome = $_POST['nName'];
@$email = $_POST['nEmail'];
@$sexo = $_POST['nSex'];
@$nascimento = $_POST['nNasc'];
@$alert = $_POST['nAlert'];
@$id_fuso = $_POST['nFuso'];

if(@$_REQUEST['botao']=="Gravar" || @$_REQUEST['botao']=="Save") {
    if ( verefFields ($login,$senha,$senha2,$email,$sexo,$nome,$nascimento,$id_fuso) ) {
        $password = encrypt($senha);
        $data_atual = date('Y-m-d h:i:s');
        $insere = "INSERT INTO user(login,name,gender,birth,email,pass,alert_time,id_timezone,create_data) VALUES('$login','$nome','$sexo','$nascimento','$email','$password','$alert','$id_fuso','$data_atual')";
        $result = mysql_query($insere) or die (mysql_error());

        if($result) {
            if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Cadastrado com sucesso!";} else { $_SESSION['mensagem'] = "Successfully registered!";}
            $_SESSION['login'] = $login;
            ob_start();
            header("Location: index.php");
            ob_end_flush();
            exit;
        }
        ob_start();
        header("Location: register.php");
        ob_end_flush();
        exit;
    }
}
if (isset($_SESSION['mensagem'])) {
    echo "<script> alert('{$_SESSION['mensagem']}');</script>";
    unset ($_SESSION['mensagem']);
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <title> <?php echo ($_SESSION['lang'] == 'pt' ? 'Registre-se || PostNotes' : 'Sign Up || PostNotes') ?></title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" type="text/css" href="_css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="_css/indexstyle.css"/>
    <link rel="stylesheet" type="text/css" href="_css/scheduleSearch.css"/>
    <script>
        function ClearAllFields () {

            document.getElementById("idUser").value = "";
            document.getElementById("idName").value = "";
            document.getElementById("idPass").value = "";
            document.getElementById("idPass2").value = "";
            document.getElementById("idEmail").value = "";
            document.getElementById("idSexM").checked = false;
            document.getElementById("idSexF").checked = false;
            document.getElementById("idNasc").value = "";
            document.getElementById("idAlert").value = "";
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
            <table>
                <tr>
                    <td colspan=2> <?php echo ($_SESSION['lang'] == 'pt' ? 'Registre-se' : 'Sign Up') ?> </td>
                </tr>
                <tr>
                    <th> <label for="idUser">Login:</label> </th>
                    <td> <input type="text" id="idUser" name="nUser" value="<?php echo @$_POST['nUser']; ?>" placeholder="<?php echo ($_SESSION['lang'] == 'pt' ? 'Escolha um login.' : 'Choose a login') ?>" maxlength="20" size="20"/>  </td>
                </tr>
                <tr>
                    <th> <label for="idPass"><?php echo ($_SESSION['lang'] == 'pt' ? 'Senha:' : 'Password:') ?></label> </th>
                    <td> <input type="password" id="idPass" name="nPass" value="<?php echo @$_POST['nPass']; ?>" placeholder="<?php echo ($_SESSION['lang'] == 'pt' ? 'Escolha sua senha.' : 'Choose your password') ?>" maxlength="20" size="20"/> </td>
                </tr>
                <tr>
                    <th> <label for="idPass2"><?php echo ($_SESSION['lang'] == 'pt' ? 'Confirme a senha:' : 'Confirm your password:') ?></label> </th>
                    <td> <input type="password" id="idPass2" name="nPass2" value="<?php echo @$_POST['nPass2']; ?>" placeholder="<?php echo ($_SESSION['lang'] == 'pt' ? 'Confirme a senha.' : 'Confirm your password') ?>" maxlength="20" size="20"/> </td>
                </tr>
                <tr>
                    <th> <label for="idName"><?php echo ($_SESSION['lang'] == 'pt' ? 'Nome:' : 'Name:') ?></label> </th>
                    <td> <input type="text" id="idName" name="nName" value="<?php echo @$_POST['nName']; ?>" placeholder="<?php echo ($_SESSION['lang'] == 'pt' ? 'Digite seu nome.' : 'Enter your name') ?>" maxlength="50" size="20"/>  </td>
                </tr>
                <tr>
                    <th> <label for="idEmail">Email:</label> </th>
                    <td> <input type="email" id="idEmail" name="nEmail" value="<?php echo @$_POST['nEmail']; ?>" placeholder="<?php echo ($_SESSION['lang'] == 'pt' ? 'Digite seu e-mail.' : 'Enter your email') ?>" maxlength="30" size="20"/>  </td>
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
                    <th> <?php echo ($_SESSION['lang'] == 'pt' ? 'Alerta Padrão:' : 'Default Alert:') ?> </th>
                    <td>
                    <?php
                        @$alert = ($alert < 0?$alert:60);
                    ?>

                        <select id="idAlert" name="nAlert">
                            <option value="0" disabled class="titulo"> <?php echo ($_SESSION['lang'] == 'pt' ? 'Selecione um valor.' : 'Select a value.') ?> </option>
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
                <tr>
                    <th> <label for="idFuso"><?php echo ($_SESSION['lang'] == 'pt' ? 'Fuso horário:' : 'Time zone:') ?></label> </th>
                    <td> <?php

                        $query = "SELECT * FROM time_zone ORDER BY id_timezone";
                        $result = mysql_query($query) or die (mysql_error());

                        ?>
                        <select  id="idFuso" name="nFuso">
                            <option class="titulo" value="" <?php echo "" == @$id_fuso?"selected":"" ?> disabled > ..:: <?php echo ($_SESSION['lang'] == 'pt' ? 'Escolha o fuso horário' : 'Choose the time zone') ?> ::.. </option>
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
                <tr>
                    <td colspan=2>
                        <input type="submit" id="gravar" name="botao" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Gravar' : 'Save') ?>"/>
                        <input type="button" id="limpar" name="botao" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Limpar' : 'Clean') ?>" onclick="ClearAllFields()"/>
                        <p style="margin-top: 20px; text-align: center; font-size: 14pt;">
                            <a href = "javascript:void(0)" onclick = "$('html, body').animate({scrollTop: 0}, 'slow');document.getElementById('light').style.display='block';document.getElementById('fade').style.display='block';disable_scroll();"><?php echo ($_SESSION['lang'] == 'pt' ? 'Já possuo uma conta' : 'I already have an account') ?></a>
                        </p>
                    </td>
                </tr>
            </table>
        </form>
    </div>
</div>

<?php require("footer.php"); ?>

</body>
</html>