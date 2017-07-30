<?php
include('config.php');
include('function.php');
?>

<html lang="pt-br">
<head>
    <title> PostNotes - ADMIN </title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" type="text/css" href="_css/base.css" />
    <script>
        function ClearAllFields (){
            document.getElementById("name").value = "";
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
                </ul>
        </div>
    </nav>

    <header>
        <h1> Ola Administrador </h1>
        <h2>  </h2>
    </header>

    <div id="corpo">
        <form action="#" method="POST">
        <fieldset>

            <legend>Relatório de usuários:</legend>
            <p>Nome: <input type="text" name="name" id="name" value="<?php echo @$_POST['name']?>" placeholder="Digite um nome"/></p>
            <p>Login: <input type="text" name="login" id="loginfield" value="<?php echo @$_POST['loginname']?>" placeholder="Digite um login"/></p>
            <p>Data inicio: <input type="date" name="nDateFilter1" id="idDateFilter1" value="<?php echo @$_POST['nDateFilter1']; ?>"/></p>
            <p>Data fim: <input type="date" name="nDateFilter2" id="idDateFilter2" value="<?php echo @$_POST['nDateFilter2']; ?>"/></p>
            <p>Ordenar por: <select name="orderUserReport" id="orderUserReport">
                    <option value="">...:: Selecione Aqui ::...</option>
                    <option value="login" <?php echo(@$_POST['orderUserReport']=='login' ? 'selected' : "")?>>Login</option>
                    <option value="name" <?php echo(@$_POST['orderUserReport']=='name' ? 'selected' : "")?>>Nome</option>
                    <option value="gender" <?php echo(@$_POST['orderUserReport']=='gender' ? 'selected' : "")?>>Sexo</option>
            </select></p>
            <p><input type="submit" name="button" id="" value="Procurar" />
            <input type="button" name="button" id="clear" value="Limpar" onclick="ClearAllFields()"/></p>

        </fieldset>
        </form>
        <?php if (@$_REQUEST['button'] == "Procurar") { ?>
        <h2>Relatorio</h2>
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
            @$name = $_POST['name'];
            @$gender = $_POST['gender'];
            @$order = $_POST['orderUserReport'];

            $query = "SELECT *
				      FROM user
				      WHERE name IS NOT NULL";
            $query .= ($name ? " AND name LIKE '%$name%' " : "");
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
                <td id="user_birth"><?php echo $coluna['birth']; ?></td>
                <td id="user_email"><?php echo $coluna['email']; ?></td>
            </tr>
            <?php
            }
            ?>
        </table>
    </div>
    <?php } ?>

    <footer> Tecnorav&copy; - PostNotes </footer>

</body>


</html>