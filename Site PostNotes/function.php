<?php
@session_start();


function getUserLanguage() {
	$idioma =substr($_SERVER["HTTP_ACCEPT_LANGUAGE"],0,2);
	return $idioma;
}
function validateDate($date, $format = 'Y-m-d H:i:s') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
/* Criptografia de senha */

function encrypt($password){

    $first = md5($password);

    $second = hash('sha512',$first);

    return $second;

}
function editUnote ($id_note,$operation) {
    /*
     * $operation - Legenda:
     * change - Adicionar ou Excluir a nota de unotes
     *
    */

    if ($operation == "delete") {
        $query = "DELETE FROM notes WHERE id_notes = '$id_note' AND ( creator = '{$_SESSION['login']}' OR id_schedule IN ( SELECT schedule.id FROM schedule INNER JOIN uschedule ON schedule.id = uschedule.id_schedule WHERE ( uschedule.login = '{$_SESSION['login']}' AND uschedule.rank = 2 ) OR schedule.administrator = '{$_SESSION['login']}' ) )";
        $result = mysql_query($query) or die (mysql_error());
        return true;
    }

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
			if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Anotação não pertence as suas agendas!";
				} else {$_SESSION['mensagem'] = "Note doesn't belong to your shedules!";}
        return false;
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
    if (!$note_exist && $operation == "change") {
        $query = "INSERT INTO unotes(id_notes,login,alarm) VALUES('$id_note','{$_SESSION['login']}','$alert_new_time')";
        $result = mysql_query($query) or die (mysql_error());
        return true;
    } else if ($note_exist && $operation == "change") {
        $query = "DELETE FROM unotes WHERE id_notes = '$id_note' AND login = '{$_SESSION['login']}'";
        $result = mysql_query($query) or die (mysql_error());
        return true;
    } else {
        if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Erro!";} else {$_SESSION['mensagem'] = "Error!";}
    }

    return false;
}

/* Vereficações de campos */
function verefFields ($login,$password,$password2,$email,$sexo,$nome,$nascimento,$fuso) {
    unset ($_SESSION['mensagem']);

    if (verefLogin($login) != "")
        $_SESSION['mensagem'] = verefLogin($login);

    if (verefPass($password) != "")
        $_SESSION['mensagem'] = verefPass($password);

    if ($password2 != $password)
        if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Senhas incompatíveis";} else {$_SESSION['mensagem'] = "Incompatible passwords";}

    if (!verefEmail($email))
        if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "E-mail inválido!";} else {$_SESSION['mensagem'] = "Invalid e-mail!";}

    if ($sexo == "")
        if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Selecione um sexo!";} else {$_SESSION['mensagem'] = "Select a gender!";}

    if ($nome == "")
        if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Nome está em branco!";} else {$_SESSION['mensagem'] = "Type the name correctly!";}

    if ($nascimento == "" || !verefNasc($nascimento))
        if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Data de nascimento inválida!";} else {$_SESSION['mensagem'] = "Birth date invalid!";}

    if ($fuso == "")
        if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Escolha um fuso horário!";} else {$_SESSION['mensagem'] = "Choose a time zone!";}

    if (isset($_SESSION['mensagem']))
        return false;

    return true;
}

function verefNasc ($date) {
    $date .= " 00:00:00";
    if (!validateDate($date))
        return false;

    if (strtotime($date) < strtotime(date('Y-m-d H:i:s', strtotime("-1 year", date('Y-m-d H:i:s') ))) || strtotime($date) >= strtotime(date('Y-m-d H:i:s'))) {
        return false;
    }
    return true;
}
function verefAlarm ($date) {
    if (!validateDate($date))
        return false;
    $current_date = date('Y-m-d H:i:s');
    if (strtotime($date) < strtotime(date('Y-m-d H:i:s'))) {
        return false;
    }
    return true;
}

function verefSchedule ($nome,$privacidade,$tipo){
    if (verefNome($nome) != "")
        $_SESSION['mensagem'] = verefNome($nome);

    if ($privacidade != "")
        if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Escolha uma privacidade";} else {$_SESSION['mensagem'] = "Choose the privacity";}

    if ($tipo != "")
        if($_SESSION['lang'] == "pt"){$_SESSION['mensagem'] = "Escolha um tipo!";} else {$_SESSION['mensagem'] = "Choose the type!";}
}

function verefNome ($nome) {
    if ( strlen($nome) < 6 || strlen($nome) > 30 )
        if($_SESSION['lang'] == "pt"){return "O nome deve conter entre 6 a 30 caracteres!";} else {return "The name must be between 6-30 characters!";}
    if( !ctype_alnum( $nome ) )
        if($_SESSION['lang'] == "pt"){return "Nome inválido, use apenas Letras e Números (ç e acentos não)!";} else {return "Invalid name, use just letters and numbers (don't use ç or accents)!";}
    return "";
}

function verefLogin ($login) {
    if ( strlen($login) < 8 || strlen($login) > 20 )
        if($_SESSION['lang'] == "pt"){return "Login deve conter entre 8 a 20 caracteres!";} else {return "Login must contain between 8-20 characters!";}
    if( !ctype_alnum( $login ) )
        if($_SESSION['lang'] == "pt"){return "Login inválido, use apenas Letras e Números (ç e acentos não)!";} else {return "Invalid login, use just letters and numbers (don't use ç or accents)!";}
    return "";
}

function verefPass ($password) {
    if ( strlen($password) < 8 || strlen($password) > 20 )
        if($_SESSION['lang'] == "pt"){return "Senha deve conter entre 8 a 20 caracteres!";} else {return "Password must be between 8-20 characters!";}
    return "";
}

function verefEmail($endereço) {
    $Syntaxe='#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#';
    if(preg_match($Syntaxe,$endereço))
        return true;
    else
        return false;
}

function verefNull ($field) {
    if ($field == "")
        return false;
    else
        return true;
}