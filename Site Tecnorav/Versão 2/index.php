<?php
include('config.php');

@session_start();

if(isset($_SESSION['login'])){
    ob_start();
    header("Location: reportUser.php");
    ob_end_flush();
}

if(@$_REQUEST['botao'] == "Entrar"){
    if(@$_POST['login'] == "admin" && @$_POST['senha'] == "admin"){
        @session_unset();
        @session_destroy();
        @session_start();
        @$_SESSION['login'] = "admin";
        ob_start();
        header("Location: reportUser.php");
        ob_end_flush();
    } else if(@$_POST['login'] != "admin" || @$_POST['senha'] != "admin"){
        echo "<script> alert('Login e/ou senha inválidos');top.location.href='{$_SERVER['PHP_SELF']}'; </script>";
    }
}
?>

<html lang="pt-br">
<head>
    <title> PostNotes - ADMIN </title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" type="text/css" href="_css/base.css" />
</head>


<body>
<header class="login">

</header>

<div id="corpo">
    <img src="_img/LogoAdmin.png" class="imagem">
    <form action="#" method="post">

        <fieldset id="Login">
            <table id="login">
            <legend></legend>
            <tr>
            <td><p>Login: </p></td>
            <td><p><input type="text" name="login" id="login" value="<?php echo @$_POST['login']?>" placeholder="Digite o login de administrador"/></p> </td>
            </tr>
            <tr>
            <td><p>Administrador:</p></td>
            <td><p> <input type="password" name="senha" id="senha" placeholder="Digite a senha do administrador" /></p></td>
            </tr>
            <tr>
            <td colspan="2"> <p> <input type="submit" name="botao" value="Entrar" /> </p> </td>
            </tr>
        </table>
        </fieldset>

</div>
<footer> Tecnorav&copy; - PostNotes </footer>

</body>


</html>
