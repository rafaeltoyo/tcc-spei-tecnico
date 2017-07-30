<!DOCTYPE html>
<?php

@session_start();

?>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"/>
    <link rel="icon" type="icon" href="_images/iconPN.ico"
    <link rel="stylesheet" type="text/css" href="_css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="_css/footer.css" />
    <link rel="stylesheet" type="text/css" href="_css/indexstyle.css" />

    <script src="_js/jquery-1.11.1.min.js"></script>
    <script src="_js/jquery.easing.1.3.js"></script>
    <script>
        function Redirect(url)
        {
            location.href = url;
        }

        function dropdown () {
            if (document.getElementById("dropdown").className = "active") {
                document.getElementById("dropdown").className = "";
            } else {
                document.getElementById("dropdown").className = "active";
            }
        }
        function login ( type ) {
            if (type == 1) {
                $('html, body').animate({scrollTop: 0}, 'slow');
                document.getElementById('light').style.display='block';
                document.getElementById('fade').style.display='block';
                disable_scroll();
            } else if (type == 2) {
                document.getElementById('light').style.display='none';
                document.getElementById('fade').style.display='none';
                enable_scroll();
            }
        }
        // left: 37, up: 38, right: 39, down: 40,
        // spacebar: 32, pageup: 33, pagedown: 34, end: 35, home: 36
        var keys = [32, 33, 34, 35, 36, 37, 38, 39, 40];

        function preventDefault(e) {
            e = e || window.event;
            if (e.preventDefault)
                e.preventDefault();
            e.returnValue = false;
        }

        function keydown(e) {
            for (var i = keys.length; i--;) {
                if (e.keyCode === keys[i]) {
                    preventDefault(e);
                    return;
                }
            }
        }

        function wheel(e) {
            preventDefault(e);
        }

        function disable_scroll() {
            if (window.addEventListener) {
                window.addEventListener('DOMMouseScroll', wheel, false);
            }
            window.onmousewheel = document.onmousewheel = wheel;
            document.onkeydown = keydown;
        }

        function enable_scroll() {
            if (window.removeEventListener) {
                window.removeEventListener('DOMMouseScroll', wheel, false);
            }
            window.onmousewheel = document.onmousewheel = document.onkeydown = null;
        }
    </script>
</head>
<body>
<div id="light" class="white_content">
    <p style="text-align: right;"><a href = "javascript:void(0)" onclick = "login(2)">
        <img src="_images/close_icon.png" class="icon" id="cancel"/>
    </a></p>
    <form action="login.php" method="post" id="login">
        <p> <?php echo ($_SESSION['lang'] == 'pt' ? 'Entrar' : 'Sign in') ?>: </p>
        <p><input type="text" id="idLoginLogin" name="nLoginLogin" value="" placeholder="<?php echo ($_SESSION['lang'] == 'pt' ? 'Digite seu login' : 'Type your login') ?>" required /></p>
        <p><input type="password" id="idLoginPass" name="nLoginPass" value="" placeholder="<?php echo ($_SESSION['lang'] == 'pt' ? 'Digite sua senha' : 'Type your password') ?>" required /></p>
        <p><input type="submit" id="idEnter" name="button" value="<?php echo ($_SESSION['lang'] == 'pt' ? 'Entrar' : 'Enter') ?>"/></p>
    </form>
</div>
<a href = "javascript:void(0)" onclick = "login(2)">
    <div id="fade" class="black_overlay"></div>
</a>




<nav>
    <div id="newmenu_body">
        <div style="display:inline-block; width:50%; text-align: left;">
        <ul class="newmenu_left">
            <a href="index.php"><li class="newmenu_button"> <img src="_images/home_icon.png" class="icon" /> <?php echo ($_SESSION['lang'] == 'pt' ? 'Página Inicial' : 'Home') ?></li></a>
            <a href="download.php"><li class="newmenu_button"> <img src="_images/down_icon.png" class="icon" /> <?php echo ($_SESSION['lang'] == 'pt' ? 'Baixar' : 'Download') ?> </li></a>
        </ul>
        </div>
        <div style="display:inline-block; width:49%; text-align: right;">
        <ul class="newmenu_right">
        <?php if(!isset($_SESSION['login'])){ ?>
            <a href="register.php"><li>
                <img src="_images/signup_icon.png" class="icon" /><?php echo ($_SESSION['lang'] == 'pt' ? 'Registre-se' : 'Sign Up') ?>
            </li></a>
            <a href = "javascript:void(0)" onclick = "login(1)">
            <li class="newmenu_login">
                <img src="_images/login_icon.png" class="icon" /> <?php echo ($_SESSION['lang'] == 'pt' ? 'Entrar' : 'Login') ?>
            </li>
            </a>
        <?php } else { ?>
            <li class="newmenu_msg">
                <img src="_images/user_icon.png" class="icon" />
                <?php echo ($_SESSION['lang']=="pt" ? "Bem vindo $_SESSION[login]" : "Welcome $_SESSION[login]")?>
            </li>
            <li class="newmenu_options" onclick="dropdown()">
                <img src="_images/option_icon.png" class="icon" />
                <ul id="dropdown">
                    <a href="viewNotes.php"><li><?php echo ($_SESSION['lang'] == 'pt' ? 'Suas Anotações' : 'Your Notes') ?></li></a>
                    <a href="viewSchedule.php"><li><?php echo ($_SESSION['lang'] == 'pt' ? 'Suas Agendas' : 'Your Schedules') ?></li></li></a>
                    <a href="invitations.php"><li><?php echo ($_SESSION['lang'] == 'pt' ? 'Seus Convites' : 'Your Invitations') ?></li></a>
                    <a href="editProfile.php"><li><?php echo ($_SESSION['lang'] == 'pt' ? 'Editar Perfil' : 'Edit Profile') ?></li></a>
                    <a href="login.php?logout=true"><li><?php echo ($_SESSION['lang'] == 'pt' ? 'Sair' : 'Logout') ?></li></a>
                </ul>
            </li>
        <?php } ?>
        </ul>
        </div>
    </div>
</nav>

</body>
</html>