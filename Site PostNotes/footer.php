<!DOCTYPE html>
<?php
function alteraIdioma ($idioma) {
    $_SESSION['lang'] = $idioma;
    echo "<script> alert('test') </script>";
    return true;
}
?>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"/>
</head>
<body>
    <footer>
        <div style="margin: 0 auto; padding: 20px; width: 700px; background-color: rgba(0,0,0,.1); border-bottom-left-radius: 15px; border-top-right-radius: 15px;">
            <p style="color:#D9D9D9">PostNotes &copy; - TecnoRav</p>
        <ul id="mapa_site">
            <li class="title"><?php echo ($_SESSION['lang'] == 'pt' ? 'Mapa do site:' : 'Site map:') ?></li>
            <li><a href="index.php" target="_blank"><?php echo ($_SESSION['lang'] == 'pt' ? 'Página Inicial' : 'Home') ?></a></li>
            <li><a href="download.php" target="_blank"><?php echo ($_SESSION['lang'] == 'pt' ? 'Baixar' : 'Download') ?></a></li>
            <li>
                <a href = "javascript:void(0)" onclick = "login(1);">
                    <?php echo ($_SESSION['lang'] == 'pt' ? 'Entrar' : 'Log in') ?>
                </a>
            </li>
            <li><a href="register.php" target="_blank"><?php echo ($_SESSION['lang'] == 'pt' ? 'Registre-se' : 'Sign Up') ?></a></li>
            <li>
                <p class="title_ul"><?php echo ($_SESSION['lang'] == 'pt' ? 'Opções:' : 'Options:') ?></p>
                <p>- <a href="viewNotes.php" target="_blank"><?php echo ($_SESSION['lang'] == 'pt' ? 'Ver Notas' : 'View Notes') ?></a></p>
                <p>- <a href="viewSchedule.php" target="_blank"><?php echo ($_SESSION['lang'] == 'pt' ? 'Ver Agendas' : 'View Schedules') ?></a></p>
                <p>- <a href="createSchedule.php" target="_blank"><?php echo ($_SESSION['lang'] == 'pt' ? 'Criar Agenda' : 'Create Schedule') ?></a></p>
                <p>- <a href="searchSchedule.php" target="_blank"><?php echo ($_SESSION['lang'] == 'pt' ? 'Procurar Agenda' : 'Search Schedule') ?></a></p>
                <p>- <a href="invitations.php" target="_blank"><?php echo ($_SESSION['lang'] == 'pt' ? 'Seus Convites' : 'Your Invitations') ?></a></p>
                <p>- <a href="editProfile.php" target="_blank"><?php echo ($_SESSION['lang'] == 'pt' ? 'Editar Perfil' : 'Edit Profile') ?></a></p>
            </li>
			<li>
				<p class="title_ul"><?php echo ($_SESSION['lang'] == 'pt' ? 'Idioma:' : 'Language:') ?></p>
				<p><a href="<?php echo $_SERVER['REQUEST_URI']."?idioma=pt" ?>"><img src="_images/bandeira_brasil.png" /></a>
				&nbsp;<a href="<?php echo $_SERVER['REQUEST_URI']."?idioma=en" ?>"><img src="_images/bandeira_uk.png" /></a></p>
			</li>
        </ul>
        </div>

    </footer>
</body>
</html>