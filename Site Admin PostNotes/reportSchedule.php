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

            <legend>Relatório de agendas:</legend>
            <p>Agenda: <input type="text" name="nome" id="name" value="<?php echo @$_POST['name']?>" placeholder="Digite o nome da agenda"/></p>
            <p>Administrador: <input type="text" name="login" id="loginfield" value="<?php echo @$_POST['loginname']?>" placeholder="Digite o administrador"/></p>
            <p>Ordenar por: <select name="orderUserReport" id="orderUserReport">
                    <option value="">...:: Selecione Aqui ::...</option>
                    <option value="name" <?php echo(@$_POST['order']=='name' ? 'selected' : "")?>>Schedule</option>
                    <option value="administrator" <?php echo(@$_POST['order']=='administrator' ? 'selected' : "")?>>Administrator</option>
                </select></p>
            <p><input type="submit" name="button" id="" value="Procurar" />
                <input type="button" name="button" id="clear" value="Limpar" onclick="ClearAllFields()"/></p>

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
        @$administrator = $_POST['administrator'];
        @$name = $_POST['name'];
        @$order = $_POST['order'];

        $query = "SELECT *
				  FROM schedule
				  WHERE administrator IS NOT NULL";
        $query .= ($administrator ? " AND administrator LIKE '%$administrator%' " : "");
        $query .= ($name ? " AND name LIKE '%$name%' " : "");
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
</div>
<?php } ?>

<footer> Tecnorav&copy; - PostNotes </footer>

</body>


</html>