<!DOCTYPE html>
<?php
include ("config.php");
?>
<html lang="pt-br">
<head>
    <title> PostNotes - MANUAL </title>
    <meta charset="UTF-8"/>
    <script src="_js/jquery.easing.1.3.js"> </script>
    <script src="_js/jquery-1.11.1.min.js"> </script>
</head>
<body>
<?php
    include("menu.php");
?>

<header id="cabecalho">

</header>
<div id="min_size">
<div id="conteudo">
    <h1>O que é o PostNotes</h1><br/>
	<p>O sistema PostNotes foi desenvolvido para organizar anotações, onde todas elas são agrupadas por agendas que servem como um recipiente.</p>
    <h1>Como utilizar o PostNotes</h1><br/>
	<h2>Primeiros passos</h2><br/>
	<p>- Crie uma agenda:</p>
	<p>Após estar logado no PostNotes será possivel vizualizar no menu, a opção "Suas Agendas"</p>
    <p style="text-decoration: underline;"><a href="createSchedule.php" style="font-style: underline;" target="_blank">Clique aqui para criar uma agenda</a></p>
</div>
</div>


<?php require("footer.php"); ?>

</body>
</html>