<?php
    include ("config.php");

    if (strcasecmp('formulario-ajax', $_POST['metodo']) == 0) {
        $id_note = $_POST['id-note'];
        $id_schedule = $_POST['id-schedule'];

    /*
     * VEREFICADOR 1:
     * Procura a nota com o ID passado pela função, busca a agenda que essa nota está e verefica se o usuário da sessão está nela.
     * Retorna info da nota se ela pertence a uma agenda sua.
     * ( Verefica se não se trata de uma nota inválida para você ).
    */
        $query = "SELECT * FROM notes WHERE id_notes = '$id_note' AND id_schedule IN ( SELECT schedule.id FROM schedule INNER JOIN uschedule ON schedule.id = uschedule.id_schedule WHERE uschedule.login = '{$_SESSION['login']}' )";
        $result = mysql_query($query) or die (mysql_error());
        $get_note_info = mysql_fetch_assoc($result);
        if ($get_note_info == null) {
            echo "erro";
        } else {
            $alert_notes_time = $get_note_info['date'];
        }

        /* VEREFICADOR 2: Verefica se a anotação da Url já está salva nas notas do usuário. */
        $note_exist = false;
        $query = "SELECT * FROM unotes WHERE id_notes = '$id_note' AND login = '{$_SESSION['login']}'";
        $result = mysql_query($query) or die (mysql_error());
        while ($coluna = mysql_fetch_array($result)) {
            $note_exist = true;
        }

        /* Pegar o tempo de alerta padrão do usuário */
        $query = "SELECT * FROM user WHERE login = '{$_SESSION['login']}'";
        $result = mysql_query($query) or die (mysql_error());
        while ($coluna = mysql_fetch_array($result)) { $alert_user_time=$coluna['alert_time']; }

        /* Gera um horário de alerta baseado na data da nota e o tempo padrão do usuário */
        $alert_new_time = date("Y-m-d H:i:s", strtotime($alert_notes_time) - $alert_user_time*60);

        /* Se encontrado algum dado: */
        if (!$note_exist) {
            $query = "INSERT INTO unotes(id_notes,login,alarm) VALUES('$id_note','{$_SESSION['login']}','$alert_new_time')";
            $result = mysql_query($query) or die (mysql_error());

            $query = "SELECT time_zone.* FROM time_zone INNER JOIN user ON time_zone.id_timezone = user.id_timezone WHERE user.login LIKE '{$_SESSION['login']}'";
            $result = mysql_query($query) or die (mysql_error());
            $coluna = mysql_fetch_assoc($result);

            $timezone = -$coluna['utc'];

            $pedacos_date = explode(" ", date('Y-m-d H:i:s', strtotime(-$timezone." hours",strtotime($alert_new_time))) );

            $date = explode("-",$pedacos_date[0]);
            $time = explode(":",$pedacos_date[1]);

            $texto_new_alarme = ($_SESSION['lang']=="pt" ? "Alarme atual: ".$time[0].":".$time[1]." ".$date[2]."/".$date[1]."/".$date[0] : "Current alarm: ".$time[0].":".$time[1]." ".$date[0]."/".$date[1]."/".$date[2]);

            echo "add"."-OLHAQUEBRA-".$texto_new_alarme;
        } else if ($note_exist) {
            $query = "DELETE FROM unotes WHERE id_notes = '$id_note' AND login = '{$_SESSION['login']}'";
            $result = mysql_query($query) or die (mysql_error());
            echo "del";
        } else {
            echo "erro";
        }
    }
    if (strcasecmp('updatealarm', $_POST['metodo']) == 0) {
        $id_note = $_POST['id-note'];
        $new_alarm = $_POST['new-alarm-date']." ".$_POST['new-alarm-time'].":00";

        if (!validateDate($new_alarm)) {
            echo ($_SESSION['lang']=="pt" ? "Alarme inválido" : "Invalid alarm");
            exit;
        }

        $query = "SELECT alarm FROM unotes WHERE login LIKE '{$_SESSION['login']}' AND id_notes = '$id_note'";
        $result_alarm = mysql_query($query) or die (mysql_error());
        $alarme = mysql_fetch_assoc($result_alarm);

        $current_alarm = $alarme['alarm'];

        $query = "SELECT time_zone.* FROM time_zone INNER JOIN user ON time_zone.id_timezone = user.id_timezone WHERE user.login LIKE '{$_SESSION['login']}'";
        $result = mysql_query($query) or die (mysql_error());
        $coluna = mysql_fetch_assoc($result);

        $timezone = -$coluna['utc'];

        $query = "SELECT date FROM notes WHERE id_notes = '$id_note'";
        $result = mysql_query($query) or die (mysql_error());
        $coluna = mysql_fetch_assoc($result);

        $limit_date = $coluna['date'];

        $new_alarm_utc = date( "Y-m-d H:i:s" , strtotime($timezone." hours",strtotime($new_alarm)) );

        if (strtotime ($new_alarm_utc) > strtotime($limit_date)) {
            echo ($_SESSION['lang']=="pt" ? "Alarme inválido" : "Invalid alarm");
            exit;
        }

        $query = "UPDATE unotes SET alarm = '$new_alarm_utc' WHERE login LIKE '{$_SESSION['login']}' AND id_notes = '$id_note'";
        $result_alarm = mysql_query($query) or die (mysql_error());

        $pedacos_date = explode(" ", date('Y-m-d H:i:s', strtotime(-$timezone." hours",strtotime($new_alarm_utc))) );

        $date = explode("-",$pedacos_date[0]);
        $time = explode(":",$pedacos_date[1]);

        $texto_data_alarme = ($_SESSION['lang']=="pt" ? "Alarme atual: ".$time[0].":".$time[1]." ".$date[2]."/".$date[1]."/".$date[0] : "Current alarm: ".$time[0].":".$time[1]." ".$date[0]."/".$date[1]."/".$date[2]);


        echo ($_SESSION['lang']=="pt" ? "Alarme atualizado" : "Alarm updated")."-OLHAQUEBRA-".$texto_data_alarme;


    }

echo "";