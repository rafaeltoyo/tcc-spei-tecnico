<?php
include('config.php');
include('function.php');
include('veref.php');

@session_start();


?>

<html lang="pt-br">
<head>
    <title> PostNotes - ADMIN </title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" type="text/css" href="_css/base.css" />
    <script>
        function ClearAllFields (){
            document.getElementById("nome").value = "";
            document.getElementById("administrador").value = "";
            document.getElementById("orderScheduleReport").value = "";
        }
    </script>
</head>


<body>
<nav id="menu">
    <div id="menu_corpo">
        <ul>
            <a href="reportUser.php"><li>RELATORIO DE USUARIOS</li></a>
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
    <form action="#" method="post">
        <fieldset id="ReportSchedule">

            <legend>Relatório de agendas:</legend>
            <table id="reportSchedule">
            <tr>
            <td>Agenda: </td>
            <td><input type="text" name="nome" id="nome" value="<?php echo @$_POST['nome']?>" placeholder="Digite o nome da agenda"/></td>
            </tr>
            <tr>
            <td>Administrador:</td>
            <td><input type="text" name="administrador" id="administrador" value="<?php echo @$_POST['administrador']?>" placeholder="Digite o nome do dono da agenda"/><td></td>
            </tr>
            <tr>
            <td> Ordenar por: </td>
            <td><select name="orderScheduleReport" id="orderScheduleReport">
                    <option value="">...:: Selecione Aqui ::...</option>
                    <option value="name" <?php echo(@$_POST['orderScheduleReport']=='name' ? 'selected' : "")?>>Agenda</option>
                    <option value="administrator" <?php echo(@$_POST['orderScheduleReport']=='administrator' ? 'selected' : "")?>>Administrador</option>
                </select>
            </td>
            </tr>
            <tr>
            <td colspan="2"> <input type="submit" name="button" id="" value="Procurar" />
                <input type="button" name="button" id="clear" value="Limpar" onclick="ClearAllFields()"/></td>
            </tr>
            </table>
        </fieldset>
    </form>
    <?php if (@$_REQUEST['button'] == "Procurar") { ?>
    <h2>Relatorio</h2>
    <table id="relatorio">
        <tr>
            <th>Nome da agenda</th>
            <th>Login do criador</th>
        </tr>
        <?php
        @$administrador = $_POST['administrador'];
        @$nome = $_POST['nome'];
        @$order = $_POST['orderScheduleReport'];

        $query = "SELECT *
				  FROM schedule
				  WHERE administrator IS NOT NULL";
        $query .= ($administrador ? " AND administrator LIKE '%$administrador%' " : "");
        $query .= ($nome ? " AND name LIKE '%$nome%' " : "");
        //$query .= ($priv_schedule ? " AND priv_schedule LIKE '$priv_schedule'" : "");

        $query .= ($order==!NULL ? " ORDER BY $order " : "");

        $result = mysql_query($query) or die (mysql_error());
        while($coluna = mysql_fetch_assoc($result)){
            ?>
            <tr>
                <td id="schedule_name"><?php echo $coluna['name']; ?></td>
                <td id="schedule_administrator"><?php echo $coluna['administrator']; ?></td>
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