<!DOCTYPE html>
<?php
include('config.php');
@session_start();
?>

<html lang="pt-br">
<head>
    <title> <?php echo ($_SESSION['lang'] == 'pt' ? 'Agendas || PostNotes' : 'Schedules || PostNotes') ?></title>
    <meta charset="UTF-8"/>
    <link rel="stylesheet" type="text/css" href="_css/scheduleFrame.css"/>
    <script src="_js/jquery.easing.1.3.js"> </script>
    <script src="_js/jquery-1.11.1.min.js"> </script>
	
	<script>
        if (window.location == window.top.location) {
            window.top.location = "viewSchedule.php";
        }
		
		//parent.alert(document.getElementById("schedules_frame").style.marginLeft);
		$(document).ready(function() {
			$('#toLeft').click(function(){
				$('html, body').animate({scrollLeft: $(document).scrollLeft() -100}, 'slow');
				return false;
			});
		});
		$(document).ready(function() {
			$('#toRight').click(function(){
				$('html, body').animate({scrollLeft: $(document).scrollLeft() +100}, 'slow');
				return false;
			});
		});
    </script>
</head>
<body>
	
	<div id="toLeft"> <div id="setas">&#60;</div> </div>
	<div id="toRight"> <div id="setas">&#62;</div> </div>
	
    <ul id="schedules_frame">
        <li id="index">
            <p><label for="createbutton"><?php echo ($_SESSION['lang'] == 'pt' ? 'Procurar Agendas:' : 'Search Schedules') ?></label></p>
            <br />
            <p><a href="searchSchedule.php" target='_parent'><input type="button" id="createbutton" value="+" /></a></p>
        </li>
    <?php
    $query = " SELECT schedule.* , uschedule.*  FROM schedule INNER JOIN uschedule ON
            schedule.id = uschedule.id_schedule WHERE uschedule.login LIKE '{$_SESSION['login']}' ";
    $result = mysql_query($query) or die (mysql_error());
    while($coluna = mysql_fetch_array($result)) {
        ?>
        <li id='schedule'>
            <a href='viewNotes.php?id=<?php echo $coluna['id'] ?>' target='_parent'>
            <div>
                <p>"<?php echo $coluna['name'] ?>"</p>
                <br />
                <?php
                    if ($_SESSION['login'] == $coluna['administrator']) {
                ?>
                    <p><label for="editbutton<?php echo $coluna['id'] ?>" style="background-color: rgba(0,0,0,.1)"><?php echo ($_SESSION['lang'] == 'pt' ? 'Editar' : 'Edit') ?></label></p>
                <?php
                    }
                ?>

            </div>
            </a>
            <a href="searchUser.php?id=<?php echo $coluna['id'] ?>" target='_parent'><input type="button" id="editbutton<?php echo $coluna['id'] ?>" value="+" style="display: none;"/></a>
        </li>
        <?php
    }


    ?>
	    <li id="lastspace"></li>
    </ul>




</body>
</html>