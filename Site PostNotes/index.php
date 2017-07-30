<!DOCTYPE html>
<?php
include ("config.php");

/*
$prefixos_br = array('187', '189', '200', '201');
$prefixo_ip = substr($_SERVER['REMOTE_ADDR'], 0, 3);

if (in_array($prefixo_ip, $prefixos_br))
{
    printf('O IP <strong>%s</strong> pertence ao Brasil.', $_SERVER['REMOTE_ADDR']);
}
else
{
    printf('O IP <strong>%s</strong> não pertence ao Brasil.', $_SERVER['REMOTE_ADDR']);
}
*/
?>
<html lang="pt-br">
<head>
    <title> <?php echo ($_SESSION['lang'] == 'pt' ? 'Página Inicial || PostNotes' : 'Home || PostNotes') ?> </title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" type="text/css" href="_css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="_css/footer.css" />
    <link rel="stylesheet" type="text/css" href="_css/indexstyle.css" />
    <script src="_js/jquery.easing.1.3.js"> </script>
    <script src="_js/jquery-1.11.1.min.js"> </script>

    <script>

        function changebg(action,id){
            if ( action == 'change' ) {
                document.getElementById("fmenu").style.backgroundImage = "url(_images/notice"+ id.toString() +".jpg)";
            } else if (action == 'return' ) {
                if ( document.getElementById("menu"+id).className == "free" )
                    document.getElementById("fmenu").style.backgroundImage = "url(_images/notice0.jpg)";
                var i = 1;
                while (i<5) {
                    if ( document.getElementById("menu"+i).className == "selected" ) {
                        document.getElementById("fmenu").style.backgroundImage = "url(_images/notice"+i.toString() +".jpg)";
                    }
                    i++;
                }
            }
        }
        function resize_corpo(size,id) {
            var delay=200;
            changetext(id);
            changestats(id);
            if (id == 0) {
                document.getElementById("fmenu").style.backgroundImage = "url(_images/notice0.jpg)";
            } else {
            }
            document.getElementById("corpo").style.height = "500px";
            changebg(id);
            setTimeout(function(){
                document.getElementById("corpo").style.height = size;
            },delay);
        }
        function changetext (id) {
            var array_of_li =  document.querySelectorAll("div#conteudo section");
            var i = 0;
            for (i=0 ; i<array_of_li.length ; i++) {
                array_of_li[i].style.display = "none";
            }
            if (id != 0) {
                document.getElementById("texto"+id).style.display = "block";
            }
        }
        function changestats (id){
            var array_of_li =  document.querySelectorAll("div#fmenu li");
            var i = 0;
            for (i=0 ; i<array_of_li.length ; i++) {
                array_of_li[i].className = "free";
            }
            if (id != 0) {
                document.getElementById("menu"+id).className  = "selected";
            }
        }
        $(document).ready(function() {
            $('li[class=free]').click(function(){
                $('body').animate({scrollTop: 400}, 'slow');
                return false;
            });
        });
    </script>
</head>
<body>
<?php
    include("menu.php");
?>

<header id="cabecalho">

</header>
<div id="min_size">
<div id="corpo" style="height: 500px;">
    <div id="fmenu">
        <div style="width: 800px; height: 100%; display: block; margin: 0 auto;">
        <ul>
            <h1> features </h1>
            <li onclick="resize_corpo('750px',1)" onmouseover="changebg('change',1)" onmouseout="changebg('return',1)" id="menu1" class="free"> <?php echo ($_SESSION['lang'] == 'pt' ? 'Orzanize-se' : 'Organize Yourself') ?> </li>
            <li onclick="resize_corpo('750px',2)" onmouseover="changebg('change',2)" onmouseout="changebg('return',2)" id="menu2" class="free"> <?php echo ($_SESSION['lang'] == 'pt' ? 'Prático' : 'Practical') ?> </li>
            <li onclick="resize_corpo('750px',3)" onmouseover="changebg('change',3)" onmouseout="changebg('return',3)" id="menu3" class="free"> Smarthphone </li>
            <li onclick="resize_corpo('750px',4)" onmouseover="changebg('change',4)" onmouseout="changebg('return',4)" id="menu4" class="free"> <?php echo ($_SESSION['lang'] == 'pt' ? 'Compartilhamento' : 'Sharing') ?> </li>
        </ul>
        </div>
    </div>

    <div id="conteudo">
        <p onclick="$('html, body').animate({scrollTop: 0}, 'slow');resize_corpo('500px',0)" style="text-align: center; font-size: 18pt; font-weight: bolder; float: right;"> x </p>
<?php if($_SESSION['lang'] == "pt"){ ?>
		<section id="texto1">
            <h1>Organize-se com agendas:</h1>
            <p>Com o Postnotes, é possível organizar todas as suas anotações através de agendas, que servem para agrupá-las por terem o mesmo assunto. Em cada agenda pode se adicionar novas anotações sempre que desejado. Essa funcionalidade é muito útil para separar os assuntos diferentes.</p><p> &nbsp;</p>
        <p>Exemplo: Agenda Colégio:</p>
       <p> Nesta agenda, o usuário pode anotar suas provas, com a data e horário de cada uma delas.</p>
        </section>

        <section id="texto2">
            <h1>Crie anotações de forma prática:</h1>
            <p>Uma das características do Postnotes é a forma prática de usá-lo. Existem várias ferramentas de anotações, porém, não são muito fáceis de utilizar devido às várias funções, que acabam enchendo a tela de botões. Por isso desenvolvemos este sistema, deixando o que é realmente útil, sendo muito mais prático do que os outros.</p>
        </section>

        <section id="texto3">
            <h1>Acesse pelo smartphone:</h1>
            <p>Com nosso aplicativo móvel para Android, você poderá acessar as suas anotações salvas mesmo se não estar com o computador por perto. Com este recurso instalado em seu smartphone, basta acessar o aplicativo para ver as anotações que você criou e as que foram salvas em sua conta. </p>
        </section>

        <section id="texto4">
            <h1>Compartilhe suas agendas:</h1>
            <p>O compartilhamento de agendas permite disponibilizar uma agenda escolhida com outros usuários. Quando é criada uma agenda, é possivel escolher se ela será pública, privada, ou que precisa de aprovação para novos usuários a acessarem. Também é possivel configurar as anotaçoes de cada agenda da forma que desejar, permitindo ao usuários criar ou somente visualizar as anotações da sua agenda.</p>
        </section>
		<?php } else { ?>
        <section id="texto1">
            <h1>Organize yourself with schedules:</h1>
            <p>On PostNotes, you can organize all your notes in schedules, which are used to group its by a similar subject. On each schedule you can add new notes whenever you want. This feature is very useful to separate different subjects.</p><p> &nbsp;</p>
        <p>Example: School Schedule:</p>
       <p> In this schedule, the user can note his tests, with the date of each one.</p>
        </section>

        <section id="texto2">
            <h1>Create notes practically:</h1>
            <p>A feature of Postnotes is the practical way to use it. There are several tools for annotations, however, are not very easy to use due to the various functions that fill the screen with buttons. So we developed this system, using what is really useful, much more practical than others.</p>
        </section>

        <section id="texto3">
            <h1>Access from smartphone:</h1>
            <p>With our mobile app for Android, you can access your saved notes even if you are not with the computer nearby. With this feature installed on your smartphone, you simply access the application to view the annotations that you have you created and those that were saved in your account. </p>
        </section>

        <section id="texto4">
            <h1>Share your schedules:</h1>
            <p>Sharing schedules allows other users to a chosen agenda. When a schedule is created, it is possible to choose if it will be public, private, or needs approval for new users to access. It is also allows to configure the annotations of each schedule the way you want, allowing users to create or only see the notes on your schedule.</p>
        </section>
		<?php } ?>
    </div>
</div>
</div>


<?php require("footer.php"); ?>

</body>
</html>