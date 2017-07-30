<!DOCTYPE html>
<?php
include ("config.php");

@session_start();

if (!isset($_SESSION['login'])) {
    if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Você precisa de uma conta para acessar esta página!";} else {$_SESSION['mensagem'] = "You need an account to access this page!";}

    header("Location: register.php");
    exit;

}

function getArq() {
    $files = scandir("_files");
    foreach ($files as $key => $file)
    {
        if ($file == '..' || $file == '.')
        {
            unset($files[$key]);
            continue;
        }
        return $files;
    }
}
?>
<html lang="pt-br">
<head>
    <title> <?php echo ($_SESSION['lang'] == 'pt' ? 'Baixar || PostNotes' : 'Download || PostNotes') ?> </title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" type="text/css" href="_css/menu.css"/>
    <link rel="stylesheet" type="text/css" href="_css/footer.css" />
    <link rel="stylesheet" type="text/css" href="_css/indexstyle.css" />
    <script>
        function changebg(idimg){
            document.getElementById("fmenu").style.backgroundImage = "url(_images/notice"+ idimg.toString() +".png)";
        }
        function resize_corpo(size) {
            var delay=10;//1 seconds
            setTimeout(function(){
                document.getElementById("corpo").style.height = size;
            },delay);
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
    <form action="baixar.php" method="post" name="download">
    <div id="tableRegister">
        <fieldset>
            <legend style="text-align: center; font-size: 14pt;">Downloads:</legend>
            <p><a href="_files/postnotes.rar"><input type="button" value="BAIXAR (Versão BETA)" /></a></p>
            <p>Nosso aplicativo desktop visa aumentar a funcionalidade de nosso sistema, funcionando como um despertador para as notas adicionadas através do nosso site.</p>
            <p>No aplicativo ainda é possível adiar o horário da notificação até o horário do salvo da nota em questão.</p>
            <?php
            /*
            $arquivos = getArq();
            $tam = sizeof($arquivos);

            for ($i=2;$i<$tam+2;$i++) {

                $ext = strtolower(strrchr($arquivos[$i],"."));
                $pre = explode("-",$arquivos[$i]);


                    ?>
                    <p><input type="submit" id="idDownload" name="nDownload" value="<?php echo $arquivos[$i]; ?>" /></p>
                <?php

            }
            */
            ?>
        </fieldset>
    </div>
    </form>
</div>

<?php require("footer.php"); ?>

</body>
</html>