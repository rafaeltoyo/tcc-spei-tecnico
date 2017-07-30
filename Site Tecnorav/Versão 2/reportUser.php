<?php
include('config.php');
include('function.php');
include('veref.php');

@session_start();

if(@$_REQUEST['botao'] == "Sair"){
    @session_unset();
    @session_destroy();
    header('Location: index.php');
}

?>

<html lang="pt-br">
<head>
    <title> PostNotes - ADMIN </title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" type="text/css" href="_css/base.css" />
    <script>
        function ClearAllFields (){
            document.getElementById("nome").value = "";
            document.getElementById("loginfield").value = "";
            document.getElementById("idDateFilter1").value = "";
            document.getElementById("idDateFilter2").value = "";
            document.getElementById("orderUserReport").value = "";
        }
    </script>
</head>


<body>
<nav id="menu">
    <div id="menu_corpo">
        <ul>
            <a href="index.php"><li>RELATORIO DE USUARIOS</li></a>
            <a href="reportSchedule.php"><li>RELATORIO DE AGENDAS</li></a>
            <a href="logout.php?logout=true"><li>SAIR</li></a>
        </ul>
    </div>
</nav>

<header>
    <h1> Olá Administrador </h1>
    <h2>  </h2>
</header>

<div id="corpo">
    <form action="#" method="POST">
        <fieldset id="ReportUser">
            <legend>Relatório de usuários:</legend>
            <table id="reportUser">
            <tr>
            <td>Nome: </td>
            <td><input type="text" name="nome" id="nome" value="<?php echo @$_POST['nome']?>" placeholder="Digite um nome"/></td>
            </tr>
            <tr>
            <td>Login:</td>
            <td><input type="text" name="login" id="loginfield" value="<?php echo @$_POST['login']?>" placeholder="Digite um login"/></td>
            </tr>
            <tr>
            <td>Data início:</td>
            <td><input type="date" name="nDateFilter1" id="idDateFilter1" value="<?php echo @$_POST['nDateFilter1']; ?>"/></td>
            </tr>
            <tr>
            <td>Data fim:</td>
            <td><input type="date" name="nDateFilter2" id="idDateFilter2" value="<?php echo @$_POST['nDateFilter2']; ?>"/></td>
            </tr>
            <tr>
            <td>Ordenar por:</td>
            <td><select name="orderUserReport" id="orderUserReport">
                    <option value="" style="display: none;">...:: Selecione Aqui ::...</option>
                    <option value="login" <?php echo(@$_POST['orderUserReport']=='login' ? 'selected' : "")?>>Login</option>
                    <option value="name" <?php echo(@$_POST['orderUserReport']=='name' ? 'selected' : "")?>>Nome</option>
                    <option value="gender" <?php echo(@$_POST['orderUserReport']=='gender' ? 'selected' : "")?>>Sexo</option>
                </select></td>
            <tr>
            <td colspan="2"><input type="submit" name="button" id="" value="Procurar" />
                <input type="button" name="button" id="clear" value="Limpar" onclick="ClearAllFields()"/></2>
                </tr>
            </table>
        </fieldset>
    </form>
    <?php if (@$_REQUEST['button'] == "Procurar") { ?>

    <table id="relatorio">
        <tr>
            <th>Nome</th>
            <th>Login</th>
            <th>Sexo</th>
            <th>Nascimento</th>
            <th>Email</th>
        </tr>
        <?php
        @$login = $_POST['login'];
        @$nome = $_POST['nome'];
        @$gender = $_POST['gender'];
        @$order = $_POST['orderUserReport'];

        $query = "SELECT *
				      FROM user
				      WHERE name IS NOT NULL";
        $query .= ($nome ? " AND name LIKE '%$nome%' " : "");
        $query .= ($login ? " AND login LIKE '%$login%' " : "");

        @$data_inicio = $_POST['nDateFilter1']." 00:00:00";
        @$data_final = $_POST['nDateFilter2']." 23:59:59";
        if ( validateDate($data_inicio) && validateDate($data_final) ) {
            if( strtotime( $_POST['nDateFilter1'] ) >= strtotime( $_POST['nDateFilter2'] ) ) {
                $data_inicio = $_POST['nDateFilter1']." 00:00:00";
                $data_final = $_POST['nDateFilter1']." 23:59:59";
            } else {
                $data_inicio = $_POST['nDateFilter1']." 00:00:00";
                $data_final = $_POST['nDateFilter2']." 23:59:59";
            }
            $query .= " AND birth between '$data_inicio' and '$data_final'";
        }

        $query .= ($order==!NULL ? " ORDER BY $order " : "");

        $result = mysql_query($query) or die (mysql_error());
        while($coluna = mysql_fetch_assoc($result)){
            ?>
            <tr>
                <td id="user_name"><?php echo $coluna['name']; ?></td>
                <td id="user_login"><?php echo $coluna['login']; ?></td>
                <td id="user_gender"><?php echo $coluna['gender']; ?></td>
                <td id="user_birth"><?php $birth = $coluna['birth'];
                    $ex_birth = explode("-",$birth);
                    echo $ex_birth[2] . "/" . $ex_birth[1] . "/" . $ex_birth[0];?></td>
                <td id="user_email"><?php echo $coluna['email']; ?></td>
            </tr>
        <?php
        }
        ?>
    </table>
    <?php } ?>
</div>


<footer> Tecnorav&copy; - PostNotes </footer>

</body>


</html>